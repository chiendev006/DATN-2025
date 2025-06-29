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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
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
                'note' => 'nullable|string|max:1000'
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
                'note.max' => 'Ghi chú không được quá 1000 ký tự'
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

            if (!$order->save()) {
                throw new \Exception('Không thể lưu đơn hàng');
            }
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

            session()->forget(['cart', 'coupons', '_old_input']);

            DB::commit();
            return redirect()->route('order.complete', $order->id)
                ->with('success', 'Đặt hàng thành công!')
                ->with('order_number', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success($orderId)
    {
        try {
            $order = Order::with('orderDetails.size', 'orderDetails.product')->findOrFail($orderId);
            $allToppings = Product_topping::all()->keyBy('id');
            return view('client.order-complete', compact('order', 'allToppings'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
    }
}
