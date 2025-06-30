<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\sanpham;
use App\Models\Product_topping; 
use App\Models\Address; 
use App\Models\Coupon; 
use App\Models\User;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function index()
    {
        $items = [];
        $cart = [];
        $subtotal = 0; 
        $discount = 0; 

        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::id())->first();
            if ($userCart) {
                $items = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();
                foreach ($items as $item) {
                    if (!$item->product) continue;

                    $productBasePrice = 0;
                    if ($item->size) {
                        $productBasePrice = $item->size->price ?? 0;
                    } else {
                        $minSizeAttribute = DB::table('product_attributes')
                            ->where('product_id', $item->product->id)
                            ->orderBy('price')
                            ->first();
                        $productBasePrice = $minSizeAttribute->price ?? 0;
                    }

                    $toppingPrice = 0;
                    if (!empty($item->topping_id)) {
                        $toppingIdString = (string) $item->topping_id;
                        $toppingIds = array_map('intval', array_filter(array_map('trim', explode(',', $toppingIdString))));
                        if (!empty($toppingIds)) {
                            $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                        }
                    }
                    $unitPrice = $productBasePrice + $toppingPrice;
                    $subtotal += $unitPrice * $item->quantity;
                }
            }
            Log::info('User cart fetched for checkout index', [
                'user_id' => Auth::id(),
                'cart_exists' => !is_null($userCart),
                'items_count' => count($items)
            ]);
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $cartItem) {
                if (!isset($cartItem['sanpham_id']) || !isset($cartItem['quantity'])) {
                    continue;
                }
                $product = Sanpham::select(['id', 'name'])->where('id', $cartItem['sanpham_id'])->first();
                if (!$product) continue;

                $basePrice = 0;
                if (isset($cartItem['size_id'])) {
                    $selectedSizeAttribute = DB::table('product_attributes')
                        ->where('product_id', $product->id)
                        ->where('id', $cartItem['size_id'])
                        ->first();
                    $basePrice = $selectedSizeAttribute->price ?? 0;
                } else {
                    $minSizeAttribute = DB::table('product_attributes')
                        ->where('product_id', $product->id)
                        ->orderBy('price')
                        ->first();
                    $basePrice = $minSizeAttribute->price ?? 0;
                }

                $toppingTotal = 0;
                if (!empty($cartItem['topping_ids'])) {
                    $toppingIdsArray = array_map('intval', array_filter(array_map('trim', explode(',', $cartItem['topping_ids']))));
                    if (!empty($toppingIdsArray)) {
                        $toppingTotal = Product_topping::whereIn('id', $toppingIdsArray)->sum('price');
                    }
                }
                $unitPrice = $basePrice + $toppingTotal;
                $quantity = intval($cartItem['quantity'] ?? 1);
                $subtotal += $unitPrice * $quantity;
            }
            Log::info('Guest cart fetched for checkout index', [
                'cart_data' => $cart,
                'cart_count' => count($cart)
            ]);
        }

        $appliedCoupons = session()->get('coupons', []);
        foreach ($appliedCoupons as $coupon) {
            $discount += ($coupon['type'] === 'percent')
                ? round($subtotal * $coupon['discount'] / 100)
                : round($coupon['discount']);
        }
        $totalAfterDiscount = max(0, round($subtotal - $discount));

        $districts = Address::all(); 
        Log::info('Districts fetched', ['districts_count' => $districts->count()]);

        session()->forget('_old_input');

        return view('client.checkout', compact('items', 'cart', 'districts', 'subtotal', 'discount', 'appliedCoupons', 'totalAfterDiscount'));
    }

    public function process(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|min:2',
                'phone_raw' => 'required|string|regex:/^[0-9]{10,11}$/', 
                'district' => 'required|exists:address,id',
                'address_detail' => 'required|string|max:255|min:10',
                'payment_method' => 'required|in:cash,banking',
                'terms' => 'required|accepted',
                'email' => 'nullable|email|max:255',
                'note' => 'nullable|string|max:1000',
                'points_used' => 'nullable|integer|min:0'
            ], [
                'name.required' => 'Vui lòng nhập họ tên',
                'name.string' => 'Họ tên phải là chuỗi ký tự',
                'name.max' => 'Họ tên không được quá 255 ký tự',
                'name.min' => 'Họ tên phải có ít nhất 2 ký tự',
                'phone_raw.required' => 'Vui lòng nhập số điện thoại',
                'phone_raw.regex' => 'Số điện thoại phải có 10-11 chữ số',
                'district.required' => 'Vui lòng chọn huyện',
                'district.exists' => 'Huyện không hợp lệ',
                'address_detail.required' => 'Vui lòng nhập địa chỉ chi tiết',
                'address_detail.string' => 'Địa chỉ phải là chuỗi ký tự',
                'address_detail.max' => 'Địa chỉ không được quá 255 ký tự',
                'address_detail.min' => 'Địa chỉ phải có ít nhất 10 ký tự',
                'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
                'terms.required' => 'Vui lòng đồng ý với điều khoản và điều kiện',
                'terms.accepted' => 'Bạn phải đồng ý với điều khoản và điều kiện',
                'email.email' => 'Email không đúng định dạng',
                'email.max' => 'Email không được quá 255 ký tự',
                'note.max' => 'Ghi chú không được quá 1000 ký tự',
                'points_used.integer' => 'Số điểm phải là số nguyên',
                'points_used.min' => 'Số điểm không được âm'
            ]);

            DB::beginTransaction();
            $total = 0;
            $orderDetails = [];

            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if (!$userCart) {
                    throw new \Exception('Giỏ hàng không tồn tại');
                }

                $cartDetails = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();
                foreach ($cartDetails as $item) {
                    if (!$item->product) {
                        continue;
                    }
                    $productBasePrice = 0;
                    if ($item->size) {
                        $productBasePrice = $item->size->price ?? 0;
                    } else {
                        $minSizeAttribute = DB::table('product_attributes')
                            ->where('product_id', $item->product->id)
                            ->orderBy('price')
                            ->first();
                        $productBasePrice = $minSizeAttribute->price ?? 0;
                    }
                    $toppingPrice = 0;
                    $toppingIds = [];
                    if (!empty($item->topping_id)) {
                        $toppingIdString = (string) $item->topping_id;
                        $toppingIds = array_map('intval', array_filter(array_map('trim', explode(',', $toppingIdString)))); // Sanitize input
                        if (!empty($toppingIds)) {
                            $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                        }
                    }
                    $unitPrice = $productBasePrice + $toppingPrice;
                    $itemTotal = $unitPrice * $item->quantity;
                    $total += $itemTotal;
                    $orderDetails[] = [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                        'product_price' => $unitPrice, 
                        'quantity' => $item->quantity,
                        'total' => $itemTotal,
                        'size_id' => $item->size_id ?? null,
                        'topping_id' => implode(',', $toppingIds),
                        'status' => 'pending',
                    ];
                }
            } else {
                $sessionCart = session()->get('cart', []);
                if (empty($sessionCart)) {
                    throw new \Exception('Giỏ hàng trống');
                }
                foreach ($sessionCart as $index => $cartItem) {
                    if (!isset($cartItem['sanpham_id']) || !isset($cartItem['quantity'])) {
                        continue;
                    }
                    $product = Sanpham::select(['id', 'name'])
                        ->where('id', $cartItem['sanpham_id'])
                        ->first();
                    if (!$product) {
                        continue;
                    }
                    $basePrice = 0;
                    if (isset($cartItem['size_id'])) {
                        $selectedSizeAttribute = DB::table('product_attributes')
                            ->where('product_id', $product->id)
                            ->where('id', $cartItem['size_id'])
                            ->first();
                        $basePrice = $selectedSizeAttribute->price ?? 0;
                    } else {
                        $minSizeAttribute = DB::table('product_attributes')
                            ->where('product_id', $product->id)
                            ->orderBy('price')
                            ->first(); 
                        $basePrice = $minSizeAttribute->price ?? 0;
                    }

                    $toppingTotal = 0;
                    $toppingIdsArray = [];

                    if (!empty($cartItem['topping_ids'])) {
                        $toppingIdsArray = array_map('intval', array_filter(array_map('trim', explode(',', $cartItem['topping_ids'])))); // Sanitize input
                        if (!empty($toppingIdsArray)) {
                            $toppingTotal = Product_topping::whereIn('id', $toppingIdsArray)->sum('price');
                        }
                    }

                    $unitPrice = $basePrice + $toppingTotal;
                    $quantity = intval($cartItem['quantity'] ?? 1);
                    $itemTotal = $unitPrice * $quantity;
                    $total += $itemTotal;
                    $orderDetails[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $itemTotal,
                        'size_id' => $cartItem['size_id'] ?? null,
                        'topping_id' => !empty($toppingIdsArray) ? implode(',', $toppingIdsArray) : null,
                        'status' => 'pending',
                    ];
                }
            }

            if (empty($orderDetails)) {
                throw new \Exception('Không có sản phẩm hợp lệ trong giỏ hàng');
            }
            $appliedCouponsData = session()->get('coupons', []);
            $couponSummaryArray = [];
            $couponTotalDiscount = 0; 

            foreach ($appliedCouponsData as $couponData) {
                $couponSummaryArray[] = [
                    'code' => $couponData['code'],
                    'discount_value' => $couponData['discount'],
                    'type' => $couponData['type']
                ];
            }
            $couponSummaryJson = json_encode($couponSummaryArray);
            $discount = 0;
            foreach ($appliedCouponsData as $coupon) {
                $discount += ($coupon['type'] === 'percent')
                    ? round($total * $coupon['discount'] / 100)
                    : round($coupon['discount']);
            }
            $couponTotalDiscount = $discount; 
            $total = max(0, round($total - $discount)); 

            // Xử lý điểm tích lũy
            $pointsUsed = 0;
            $pointsDiscount = 0;
            
            Log::info('DEBUG: Starting points processing', [
                'has_points_used' => $request->has('points_used'),
                'points_used_value' => $request->points_used ?? 'null',
                'is_logged_in' => Auth::check(),
                'user_id' => Auth::id(),
                'total_before_points' => $total
            ]);
            
            if (Auth::check() && $request->has('points_used') && $request->points_used > 0) {
                try {
                    $pointsUsed = (int) $request->points_used;
                    
                    Log::info('DEBUG: Processing points', [
                        'points_used' => $pointsUsed,
                        'user_points_before' => Auth::user()->points
                    ]);
                    
                    // Kiểm tra user có đủ điểm không
                    if (Auth::user()->points < $pointsUsed) {
                        throw new \Exception('Không đủ điểm để sử dụng');
                    }
                    
                    // Tính số tiền giảm giá từ điểm (1 điểm = 1000đ)
                    $pointsDiscount = $pointsUsed * 1000;
                    
                    Log::info('DEBUG: Points calculation', [
                        'points_used' => $pointsUsed,
                        'points_discount' => $pointsDiscount,
                        'current_total' => $total
                    ]);
                    
                    // Kiểm tra không vượt quá 50% giá trị đơn hàng
                    $maxPointsByPercent = ($total * 50) / 100;
                    $maxPointsByVnd = $maxPointsByPercent / 1000;
                    $maxPoints = min(Auth::user()->points, (int) $maxPointsByVnd);
                    
                    if ($pointsUsed > $maxPoints) {
                        throw new \Exception("Chỉ có thể sử dụng tối đa {$maxPoints} điểm cho đơn hàng này");
                    }
                    
                    // Cập nhật tổng tiền sau khi trừ điểm
                    $total = max(0, $total - $pointsDiscount);
                    
                    Log::info('DEBUG: Points processing completed', [
                        'user_id' => Auth::id(),
                        'points_used' => $pointsUsed,
                        'points_discount' => $pointsDiscount,
                        'final_total' => $total
                    ]);
                } catch (\Exception $e) {
                    Log::error('DEBUG: Error calculating points for order', [
                        'user_id' => Auth::id(),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception('Lỗi xử lý điểm: ' . $e->getMessage());
                }
            }

            $selectedAddress = Address::find($request->district);
            if (!$selectedAddress) {
                throw new \Exception('Địa chỉ không hợp lệ.');
            }
            $shippingFee = $selectedAddress->shipping_fee;
            $districtName = $selectedAddress->name;
            $total += $shippingFee; 
            if ($request->payment_method === 'banking') {
                session([
                    'vnp_order' => [
                        'name' => $request->name,
                        'phone' => $request->phone_raw, 
                        'email' => $request->email,
                        'address_id' => $selectedAddress->id,
                        'address_detail' => $request->address_detail,
                        'district_name' => $districtName,
                        'total' => $total,
                        'details' => $orderDetails,
                        'discount' => $discount,
                        'shipping_fee' => $shippingFee,
                        'user_id' => Auth::check() ? Auth::id() : null,
                        'note' => $request->note,
                        'status' => 'pending_payment',
                        'coupon_summary' => $couponSummaryJson, 
                        'coupon_total_discount' => $couponTotalDiscount,
                        'points_used' => $pointsUsed,
                        'points_discount' => $pointsDiscount,
                    ]
                ]);
                DB::commit();
                return redirect()->route('vnpay.redirect');
            }

            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : null;
            $order->name = $request->name;
            $order->phone = $request->phone_raw; 
            $order->email = $request->email;
            $order->address_id = $selectedAddress->id;
            $order->address_detail = $request->address_detail;
            $order->district_name = $districtName;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->shipping_fee = $shippingFee;
            $order->total = $total;
            $order->coupon_summary = $couponSummaryJson; 
            $order->coupon_total_discount = $couponTotalDiscount;
            $order->note = $request->note;
            $order->pay_status = $request->payment_method === 'banking' ? '1' : '0';

            // Lưu thông tin điểm sử dụng
            if ($pointsUsed > 0) {
                $order->points_used = $pointsUsed;
                $order->points_discount = $pointsDiscount;
            }

            Log::info('DEBUG: Attempting to save order', [
                'order_data' => [
                    'user_id' => $order->user_id,
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'total' => $order->total,
                    'points_used' => $order->points_used ?? 0,
                    'points_discount' => $order->points_discount ?? 0,
                    'payment_method' => $order->payment_method,
                    'status' => $order->status
                ]
            ]);

            if (!$order->save()) {
                Log::error('DEBUG: Failed to save order', [
                    'order_data' => $order->toArray(),
                    'errors' => $order->getErrors() ?? 'No errors array'
                ]);
                throw new \Exception('Không thể lưu đơn hàng');
            }

            Log::info('DEBUG: Order saved successfully', [
                'order_id' => $order->id,
                'total' => $order->total
            ]);

            foreach ($orderDetails as $detail) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $detail['product_id'];
                $orderDetail->product_name = $detail['product_name'];
                $orderDetail->product_price = $detail['product_price'];
                $orderDetail->quantity = $detail['quantity'];
                $orderDetail->total = $detail['total'];
                $orderDetail->size_id = $detail['size_id'] ?? null;
                $orderDetail->topping_id = $detail['topping_id'];
                $orderDetail->status = $detail['status'] ?? 'pending';

                if (!$orderDetail->save()) {
                    throw new \Exception('Không thể lưu chi tiết đơn hàng');
                }
            }

            if (!empty($appliedCouponsData)) {
                foreach ($appliedCouponsData as $couponData) {
                    $couponModel = Coupon::where('code', $couponData['code'])->first();
                    if ($couponModel) {

                        $order->coupons()->attach($couponModel->id);

                        $couponModel->increment('used');
                    } else {
                    }
                }
            }


            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if ($userCart) {
                    Cartdetail::where('cart_id', $userCart->id)->delete();
                    $userCart->delete();
                    Log::info('User cart cleared', ['user_id' => Auth::id()]);
                }
            }

            // Trừ điểm sau khi đơn hàng đã được lưu thành công
            if ($pointsUsed > 0) {
                try {
                    Log::info('DEBUG: Starting to deduct points', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'points_used' => $pointsUsed,
                        'user_points_before' => Auth::user()->points
                    ]);
                    
                    // Trừ điểm trực tiếp từ database bằng query
                    $user = Auth::user();
                    $newPoints = $user->points - $pointsUsed;
                    
                    // Cập nhật điểm user
                    \DB::table('users')->where('id', $user->id)->update(['points' => $newPoints]);
                    
                    // Tạo transaction record
                    \DB::table('point_transactions')->insert([
                        'user_id' => $user->id,
                        'points' => -$pointsUsed,
                        'type' => 'spend',
                        'description' => "Sử dụng điểm giảm giá đơn hàng #{$order->id}",
                        'order_id' => $order->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    Log::info('DEBUG: Points deducted successfully', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'points_used' => $pointsUsed,
                        'user_points_after' => $newPoints
                    ]);
                } catch (\Exception $e) {
                    Log::error('DEBUG: Error deducting points after order creation', [
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Không throw exception ở đây để không rollback đơn hàng đã tạo
                    // Chỉ log lỗi và tiếp tục
                }
            }

            session()->forget(['cart', 'coupons', '_old_input']);

            Log::info('DEBUG: About to commit transaction', [
                'order_id' => $order->id,
                'points_used' => $pointsUsed
            ]);

            DB::commit();
            
            Log::info('DEBUG: Transaction committed successfully', [
                'order_id' => $order->id
            ]);
            
            return redirect()->route('order.complete', $order->id)
                ->with('success', 'Đặt hàng thành công!')
                ->with('order_number', $order->id);

        } catch (\Exception $e) {
            Log::error('DEBUG: Exception caught in checkout process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success($orderId)
    {
        try {
            Log::info('DEBUG: success method called', [
                'order_id' => $orderId
            ]);
            
            $order = Order::with('orderDetails.size', 'orderDetails.product')->findOrFail($orderId);
            
            Log::info('DEBUG: Order found', [
                'order_id' => $order->id,
                'order_details_count' => $order->orderDetails->count()
            ]);
            
            $allToppings = Product_topping::all()->keyBy('id');
            return view('client.order-complete', compact('order', 'allToppings'));
        } catch (\Exception $e) {
            Log::error('DEBUG: Error in success method', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
    }
}
