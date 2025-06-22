<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\admin\Product_attributesController;
use App\Http\Controllers\Controller;
use App\Models\Product_topping;
use App\Models\Size;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\Topping;
use App\Models\Order;
use App\Models\Orderdetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProductAttribute;
use App\Models\ProductTopping;

class StaffController extends Controller
{
    public function index()
    {
        $message = session('message');
        return redirect()->route('staff.products'); // chuyển thẳng về trang sản phẩm
    }

    public function ajaxShow($id)
    {
        $product = SanPham::where('id', $id)->first();
        $category = DanhMuc::where('id', $product->id_danhmuc)->first();

        $toppings = [];
        if ($category->role != 0) {
            $toppings = \DB::table('product_topping')
                ->where('product_id', $id)
                ->select('id', 'topping', 'price')
                ->get();
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'image' => asset('storage/uploads/' . $product->image),
            'mota' => $product->mota,
            'sizes' => \DB::table('product_attributes')
                ->where('product_id', $id)
                ->select('id', 'size', 'price')
                ->get(),
            'toppings' => $toppings,
            'no_topping' => $category->role == 0
        ]);

    }

    public function getAvailableCoupons(Request $request)
    {
        $today = now();
        $coupons = \DB::table('coupons')
            ->where('is_active', 1)
            ->where(function($q) use ($today){
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $today);
            })
            ->where(function($q) use ($today){
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today);
            })
            ->get(['id', 'code', 'discount', 'type', 'min_order_value']);

        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Lưu order
            $order = new Order();
            $order->user_id = Auth::guard('staff')->user()->id;
            $order->name = 'Khách lẻ';
            $order->phone = 'N/A';
            $order->address_id = $request->input('address_id') ?? 1;
            $order->address_detail = null;
            $order->shipping_fee = 0;
            $order->payment_method = $request->payment_method ?? 'cash';
            $order->total = $request->total;
            $order->coupon_summary = $request->coupon_code;
            $order->coupon_total_discount = $request->coupon_discount ?? 0;
            $order->status = 'pending';
            $order->save();
            // Lưu chi tiết order
            foreach ($request->cart as $item) {
                $detail = new Orderdetail();
                $detail->order_id = $order->id;
                $detail->product_id = $item['product_id'];
                $detail->product_name = $item['product_name'];
                $detail->product_price = $item['product_price'];
                $detail->quantity = $item['quantity'];
                $detail->total = $item['total'];
                $detail->size_id = $item['size_id'];
                $detail->topping_id = (isset($item['toppings']) && is_array($item['toppings']))
                    ? implode(',', $item['toppings']) : '';

                $detail->status = 'pending';
                $detail->save();
            }
            if ($request->coupon_code) {
                $coupon = \DB::table('coupons')->where('code', $request->coupon_code)->first();
                if ($coupon) {
                    \DB::table('coupon_order')->insert([
                        'order_id' => $order->id,
                        'coupon_id' => $coupon->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    \DB::table('coupons')->where('id', $coupon->id)->increment('used');
                }
            }
            DB::commit();
            return response()->json(['message' => 'Đặt hàng thành công!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Có lỗi xảy ra!',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }


    public function products()
    {
        $sanpham = SanPham::all();
        $danhmuc = DanhMuc::all();
        $message = session('message');
        return view('staff.menu', compact('sanpham', 'danhmuc', 'message'));
    }

    public function productsByCategory($id)
    {
        $sanpham = SanPham::where('id_danhmuc', $id)->get();
        $danhmuc = DanhMuc::all();
        $selectedDanhmuc = DanhMuc::find($id);
        $message = session('message');
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc', 'message'));
    }
    public function orderdetailtoday()
{
    $donhangs = Order::with([
        'details.product',
        'details.size'
    ])->whereDate('created_at', Carbon::today())
    ->orderBy('created_at', 'desc')
    ->get();

    // Load thông tin topping cho nhiều topping
    foreach($donhangs as $donhang) {
        foreach($donhang->details as $detail) {
            if($detail->topping_id) {
                // Xử lý trường hợp nhiều topping (ngăn cách bởi dấu phẩy)
                $toppingIds = explode(',', $detail->topping_id);
                $toppings = [];

                foreach($toppingIds as $id) {
                    $id = trim($id); // Loại bỏ khoảng trắng
                    if($id) {
                        $topping = Product_topping::find($id);
                        if($topping) {
                            $toppings[] = $topping;
                        }
                    }
                }

                $detail->topping_list = $toppings;
            } else {
                $detail->topping_list = [];
            }
        }
    }

    $danhmuc = DanhMuc::all();
    $sanpham = SanPham::all();

    return view('staff.orderdetail', compact('donhangs', 'danhmuc', 'sanpham'));
}
    public function searchProducts(Request $request)
    {
        $danhmuc = DanhMuc::all();
        $keyword = trim($request->input('keyword'));

        if (!empty($keyword)) {
            $sanpham = Sanpham::where('name', 'like', "%$keyword%")
                        ->orWhere('mota', 'like', "%$keyword%")
                        ->get();
        } else {
            $sanpham = collect(); // hoặc Sanpham::all() nếu muốn hiện tất cả
        }

        return view('staff.menu', compact('sanpham', 'keyword' , 'danhmuc'));
    }
    public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $oldStatus = $order->status;

    $newStatus = $request->input('status');
    $order->status = $newStatus;

    // Nếu trạng thái là hủy (cancelled hoặc 4), xử lý lý do hủy
    if ($newStatus == 'cancelled' || $newStatus == 4) {
        $request->validate([
            'cancel_reason' => 'required|string|max:255'
        ], [
            'cancel_reason.required' => 'Vui lòng nhập lý do hủy đơn hàng',
            'cancel_reason.max' => 'Lý do hủy không được quá 255 ký tự'
        ]);

        $cancelReason = $request->input('cancel_reason');

        // Thêm tiền tố nếu chưa có
        if (!str_contains($cancelReason, '(Nhân viên hủy)')) {
            $cancelReason = '(Nhân viên hủy) ' . $cancelReason;
        }

        $order->cancel_reason = $cancelReason;

        // Cập nhật trạng thái thanh toán khi hủy
        if ($order->pay_status == '1') {
            $order->pay_status = '3'; // Hoàn tiền
        } else {
            $order->pay_status = '2'; // Đã hủy
        }
    } else {
        $order->cancel_reason = null;
    }

    // Nếu trạng thái là hoàn thành, tự động chuyển trạng thái thanh toán sang đã thanh toán
    if ($newStatus == 'completed' || $newStatus == 3) {
        if ($order->pay_status != '1') {
            $order->pay_status = '1'; // Đã thanh toán
        }
    }

    $order->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
}

}
