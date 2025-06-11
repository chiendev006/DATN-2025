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

        $sizes = \DB::table('product_attributes')
            ->where('product_id', $id)
            ->select('id', 'size', 'price')
            ->get();

        $toppings = \DB::table('product_topping')
            ->where('product_id', $id)
            ->select('id', 'topping', 'price')
            ->get();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'image' => asset('storage/uploads/' . $product->image),
            'mota' => $product->mota,
            'sizes' => $sizes,
            'toppings' => $toppings,
        ]);
    }

    // use DB, Order, OrderDetail, ...
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
        $donhangs = Order::with('details')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

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
}
