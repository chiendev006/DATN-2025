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
use Illuminate\Support\Str;

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

    public function getAvailableCoupons()
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
            ->where(function($q){
                $q->whereNull('usage_limit')
                    ->orWhereRaw('`used` < `usage_limit`');
            })
            ->get(['id', 'code', 'discount', 'type', 'min_order_value', 'usage_limit', 'used']);

        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        \Log::info('DEBUG_POINT: store method called', [$request->all()]);
        DB::beginTransaction();
        try {
            // Lưu order
            $order = new Order();
            $order->user_id = Auth::guard('staff')->user()->id;
            $order->name = 'Khách Vãng Lai';
            $order->phone = 'Không có';
            $order->address_id = $request->input('address_id') ?? 1;
            $order->address_detail = null;
            $order->shipping_fee = 0;
            $order->status = 'processing';
            $order->pay_status = '1';
            $order->payment_method = $request->payment_method;
            $order->total = $request->total;
            $order->coupon_summary = $request->coupon_code;
            $order->coupon_total_discount = $request->coupon_discount ?? 0;

            // Xử lý thông tin khách hàng để tích điểm
            $customerPhone = $request->input('customer_phone');
            $customer = null;
            if ($customerPhone) {
                $customer = \App\Models\User::where('phone', $customerPhone)->where('role', 0)->first();
                if (!$customer) {
                    $customer = \App\Models\User::create([
                        'phone' => $customerPhone,
                        'name' => 'Khách lẻ',
                        'role' => 0,
                        'password' => bcrypt(\Illuminate\Support\Str::random(8)),
                    ]);
                }
                $order->customer_id = $customer->id;
                $order->name = $customer->name;
                $order->phone = $customer->phone;
            }

            // Lưu order trước để có ID
            $order->save();

            // Xử lý sử dụng điểm nếu có
            $pointsUsed = (int) $request->input('points_used', 0);
            $pointsDiscount = 0;
            if ($customer && $pointsUsed > 0) {
                // Lấy cấu hình quy đổi điểm
                $vndPerPoint = (int) (\DB::table('point_settings')->where('key', 'vnd_per_point')->value('value') ?? 1000);
                $maxPoints = min($customer->points, $pointsUsed);
                $pointsDiscount = $maxPoints * $vndPerPoint;
                
                \Log::info('DEBUG_POINT: Creating point transaction', [
                    'order_id' => $order->id,
                    'customer_id' => $customer->id,
                    'points_used' => $pointsUsed,
                    'max_points' => $maxPoints,
                    'points_discount' => $pointsDiscount
                ]);
                
                // Trừ điểm
                $customer->points -= $maxPoints;
                $customer->save();
                
                // Ghi log transaction
                try {
                    \DB::table('point_transactions')->insert([
                        'user_id' => $customer->id,
                        'points' => -$maxPoints,
                        'type' => 'spend',
                        'description' => 'Sử dụng điểm giảm giá đơn hàng POS #' . $order->id,
                        'order_id' => $order->id,
                        'created_by' => Auth::guard('staff')->user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    \Log::info('DEBUG_POINT: Point transaction created successfully', [
                        'order_id' => $order->id,
                        'transaction_points' => -$maxPoints
                    ]);
                } catch (\Exception $e) {
                    \Log::error('DEBUG_POINT: Failed to create point transaction', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
                
                // Cập nhật order
                $order->points_used = $maxPoints;
                $order->points_discount = $pointsDiscount;
                $order->total -= $pointsDiscount;
                $order->save();
            }
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

                $detail->status = $order->status;
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

            // Tích điểm cho khách hàng nếu có
            // (ĐÃ BỎ, chuyển sang updateStatus khi đơn hoàn thành)
            // if ($customer) {
            //     ...
            // }

            DB::commit();
            $title = '<strong>Nhân viên đặt đơn thành công:</strong> <br>';
            $content1 = "<span style='color: red;'>Tên khách:</span> <b>$order->name</b> <br>";
            $content2 = "<span style='color: red;'>Số điện thoại:</span> <b>$order->phone</b> <br>";
            $content3 = "<span style='color: red;'>Mã đơn hàng:</span> <b>$order->id</b> <br>";
            $content4 = "<span style='color: red;'>Tổng tiền:</span> <b>" . number_format($order->total) . " VNĐ</b> <br>";
            $content5 = $order->points_used > 0 ? "<span style='color: red;'>Sử dụng điểm:</span> <b>$order->points_used</b> (giảm <b>" . number_format($order->points_discount) . " VNĐ</b>)<br>" : '';
            $content5_coupon = '';
            if (!empty($order->coupon_summary)) {
                $coupons = json_decode($order->coupon_summary, true);
                if (is_array($coupons)) {
                    foreach ($coupons as $c) {
                        $code = $c['code'] ?? '';
                            $discount = $c['discount_value'] ?? 0;
                            $couponModel = \App\Models\Coupon::where('code', $code)->first();
                            $type = $couponModel ? $couponModel->type : '';
                            $typeText = $type === 'percent' ? 'Phần trăm' : ($type === 'fixed' ? 'VND' : $type);
                            $content5_coupon .= "<span style='color: red;'>Sử dụng mã:</span> <b>" . htmlspecialchars($code) . " (Giảm " . number_format($discount, 0, '.', '') . " $typeText)</b><br>";
                        }
                    }
                }
                $content6 = "<span style='color: red;'>Trạng thái:</span> <b>Chờ xác nhận</b> <br>";
                $content7 = "<span style='color: red;'>Thời gian đặt:</span> <b>" . $order->created_at->format('H:i d/m/Y') . "</b> <br>";
                $user_id = Auth::check() ? Auth::user()->id : null;
                \App\Models\historylog::create([
                    'user_id' => $user_id,
                    'role' => Auth::user()->role,
                    'content' => $title . $content1 . $content2 . $content3 . $content4 . $content5 . $content5_coupon . $content6 . $content7,
                ]);
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
        $vndPerPoint = (int) (\DB::table('point_settings')->where('key', 'vnd_per_point')->value('value') ?? 1000);
        return view('staff.menu', compact('sanpham', 'danhmuc', 'message', 'vndPerPoint'));
    }

    public function productsByCategory($id)
    {
        $sanpham = SanPham::where('id_danhmuc', $id)->get();
        $danhmuc = DanhMuc::all();
        $selectedDanhmuc = DanhMuc::find($id);
        $message = session('message');
        $vndPerPoint = (int) (\DB::table('point_settings')->where('key', 'vnd_per_point')->value('value') ?? 1000);
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc', 'message', 'vndPerPoint'));
    }
    public function orderdetailtoday(Request $request)
{
    $perPage = $request->input('per_page', 10); // lấy số bản ghi/trang, mặc định 10
    $donhangs = Order::with([
        'details.product',
        'details.size'
    ])->whereDate('created_at', Carbon::today())
    ->orderBy('created_at', 'desc')
    ->paginate($perPage); // dùng paginate thay vì get()

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
        $order = \App\Models\Order::findOrFail($id);

        $status = $request->input('status');
        $pay_status = $request->input('pay_status');

        // Cập nhật trạng thái đơn hàng
        $order->status = $status;

        // Cập nhật trạng thái thanh toán (nếu có)
        if ($pay_status !== null) {
            $order->pay_status = (string) $pay_status;
        }

        // Xử lý lý do hủy
        if ($status === 'cancelled' || $pay_status == '2') {
            $cancelReason = $request->input('cancel_reason');
            if ($cancelReason && !str_contains($cancelReason, '(Nhân viên hủy)')) {
                $cancelReason = '(Nhân viên hủy) ' . $cancelReason;
            }
            $order->cancel_reason = $cancelReason;
        }

        $order->save();

        // Hoàn điểm khi đơn bị hủy (nếu có)
        if ($status === 'cancelled') {
            try {
                \App::make('App\\Services\\PointService')->refundPointsFromOrder($order);
                \App::make('App\\Services\\PointService')->refundEarnedPointsFromOrder($order);
            } catch (\Exception $e) {
                \Log::error('POINT_DEBUG: Error refunding points in StaffController', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Cộng điểm khi đơn chuyển sang hoàn thành
        if ($status === 'completed' && $order->customer_id) {
            // Kiểm tra đã cộng điểm chưa (dựa vào point_transactions)
            $alreadyEarned = \DB::table('point_transactions')
                ->where('order_id', $order->id)
                ->where('user_id', $order->customer_id)
                ->where('type', 'earn')
                ->exists();
            if (!$alreadyEarned) {
                $pointsPerVnd = (int) (\DB::table('point_settings')->where('key', 'points_per_vnd')->value('value') ?? 10000);
                $points = floor($order->total / $pointsPerVnd);
                if ($points > 0) {
                    $customer = \App\Models\User::find($order->customer_id);
                    $customer->points += $points;
                    $customer->save();
                    // Ghi log
                    \DB::table('point_transactions')->insert([
                        'user_id' => $customer->id,
                        'points' => $points,
                        'type' => 'earn',
                        'description' => 'Tích điểm đơn hàng POS #' . $order->id,
                        'order_id' => $order->id,
                        'created_by' => \Auth::guard('staff')->user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        $order=Order::find($id);
        $content1='';
        $content2='';
        if($order->status=='processing'){
            $status='Đang xử lý';
        }else if($order->status=='completed'){
            $status='Đã hoàn thành';
        }else if($order->status=='cancelled'){
            $status='Đã hủy';
        }
        $cancel_reason=$order->cancel_reason;
        $title='<strong>Nhân viên cập nhật trạng thái đơn hàng:</strong> <br>';
            $content1=" *<span style='color: red;'>Mã đơn hàng:</span> <b>$order->id</b> <br>";
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>$status</b> <br>";
            $content3=" *<span style='color: red;'>Lý do hủy:</span> <b>$cancel_reason</b> <br>";


        \App\Models\historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2.$content3,
        ]);
        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function getCustomerPoint(Request $request)
    {
        $phone = $request->query('phone');
        $user = \App\Models\User::where('phone', $phone)->where('role', 0)->first();
        if ($user) {
            return response()->json(['success' => true, 'points' => $user->points]);
        } else {
            return response()->json(['success' => false, 'points' => 0]);
        }
    }

    public function getPointSettings()
    {
        $settings = \DB::table('point_settings')
            ->whereIn('key', ['min_points_to_use', 'max_points_per_order', 'vnd_per_point'])
            ->pluck('value', 'key');
        return response()->json($settings);
    }
}
