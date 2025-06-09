<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\sanpham;
use App\Models\Product_topping; 
use App\Models\Address; 
use App\Models\Coupon; // ADDED THIS LINE
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
        $subtotal = 0; // Khởi tạo subtotal
        $discount = 0; // Khởi tạo discount

        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::id())->first();
            if ($userCart) {
                $items = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();
                // Tính subtotal cho user cart
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
            // Tính subtotal cho guest cart
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

        // Lấy coupons từ session và tính discount
        $appliedCoupons = session()->get('coupons', []);
        foreach ($appliedCoupons as $coupon) {
            $discount += ($coupon['type'] === 'percent')
                ? round($subtotal * $coupon['discount'] / 100)
                : round($coupon['discount']);
        }
        $totalAfterDiscount = max(0, round($subtotal - $discount)); // Tổng sau giảm giá, chưa bao gồm ship

        $districts = Address::all(); 
        Log::info('Districts fetched', ['districts_count' => $districts->count()]);

        session()->forget('_old_input');

        // Truyền các biến mới sang view
        return view('client.checkout', compact('items', 'cart', 'districts', 'subtotal', 'discount', 'appliedCoupons', 'totalAfterDiscount'));
    }

    public function process(Request $request)
    {
        try {
            Log::info('Starting checkout process', [
                'is_logged_in' => Auth::check(),
                'request_data' => $request->all(),
                'session_cart' => session()->get('cart', [])
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'phone_raw' => 'required|string|max:15', 
                'district' => 'required|exists:address,id',
                'address_detail' => 'required|string|max:255',
                'payment_method' => 'required|in:cash,banking',
                'terms' => 'required|accepted'
            ]);

            DB::beginTransaction();

            $total = 0;
            $orderDetails = [];

            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                Log::info('Checking user cart', [
                    'user_id' => Auth::id(),
                    'cart_exists' => !is_null($userCart),
                    'cart_id' => $userCart ? $userCart->id : null
                ]);

                if (!$userCart) {
                    throw new \Exception('Giỏ hàng không tồn tại');
                }

                $cartDetails = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();
                Log::info('User cart details', [
                    'cart_id' => $userCart->id,
                    'cart_details_count' => $cartDetails->count(),
                    'cart_details' => $cartDetails->toArray()
                ]);

                foreach ($cartDetails as $item) {
                    if (!$item->product) {
                        Log::warning('Skipping cart item due to missing product', ['cart_detail_id' => $item->id]);
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
                        $toppingIdString = (string) $item->topping_id; // Explicitly cast to string
                        $toppingIds = array_map('intval', array_filter(array_map('trim', explode(',', $toppingIdString)))); // Sanitize input
                        Log::debug('Processing topping IDs for user cart item', [
                            'cart_detail_id' => $item->id,
                            'topping_ids' => $toppingIds
                        ]);

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
                        'note' => $item->note ?? null,
                        'status' => 'pending',
                    ];
                    Log::debug('Added order detail for user', [
                        'product_id' => $item->product->id,
                        'unit_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'item_total' => $itemTotal
                    ]);
                }
            } else {
                $sessionCart = session()->get('cart', []);
                Log::info('Guest Session Cart Data (before processing):', [
                    'cart_data' => $sessionCart,
                    'cart_count' => count($sessionCart)
                ]);

                if (empty($sessionCart)) {
                    throw new \Exception('Giỏ hàng trống');
                }

                foreach ($sessionCart as $index => $cartItem) {
                    Log::info('Processing Guest Cart Item:', [
                        'item_index' => $index,
                        'item' => $cartItem
                    ]);

                    if (!isset($cartItem['sanpham_id']) || !isset($cartItem['quantity'])) {
                        Log::warning('Invalid cart item structure', [
                            'item_index' => $index,
                            'item' => $cartItem
                        ]);
                        continue;
                    }

                    $product = Sanpham::select(['id', 'name'])
                        ->where('id', $cartItem['sanpham_id'])
                        ->first();
                    Log::debug('Fetched product for guest cart item', [
                        'sanpham_id' => $cartItem['sanpham_id'],
                        'product_exists' => !is_null($product),
                        'product_data' => $product ? $product->toArray() : null
                    ]);

                    if (!$product) {
                        Log::warning('Product not found for guest cart item', ['sanpham_id' => $cartItem['sanpham_id']]);
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
                            ->first(); // Get the smallest size price by default
                        $basePrice = $minSizeAttribute->price ?? 0;
                    }

                    $toppingTotal = 0;
                    $toppingIdsArray = [];

                    if (!empty($cartItem['topping_ids'])) {
                        $toppingIdsArray = array_map('intval', array_filter(array_map('trim', explode(',', $cartItem['topping_ids'])))); // Sanitize input
                        Log::debug('Processing topping IDs for guest cart item', [
                            'sanpham_id' => $cartItem['sanpham_id'],
                            'topping_ids' => $toppingIdsArray
                        ]);

                        if (!empty($toppingIdsArray)) {
                            $toppingTotal = Product_topping::whereIn('id', $toppingIdsArray)->sum('price');
                        }
                    }

                    $unitPrice = $basePrice + $toppingTotal;
                    $quantity = intval($cartItem['quantity'] ?? 1);
                    $itemTotal = $unitPrice * $quantity;
                    $total += $itemTotal;

                    Log::debug('Item Pricing (Guest)', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'base_price' => $basePrice, 
                        'topping_total' => $toppingTotal,
                        'unit_price_calculated' => $unitPrice,
                        'quantity' => $quantity,
                        'item_total' => $itemTotal,
                        'topping_ids_processed' => $toppingIdsArray
                    ]);

                    $orderDetails[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $itemTotal,
                        'size_id' => $cartItem['size_id'] ?? null,
                        'topping_id' => !empty($toppingIdsArray) ? implode(',', $toppingIdsArray) : null,
                        'note' => $cartItem['note'] ?? null,
                        'status' => 'pending',
                    ];
                }
            }

            if (empty($orderDetails)) {
                Log::error('No valid order details to process', [
                    'is_logged_in' => Auth::check(),
                    'session_cart' => session()->get('cart', [])
                ]);
                throw new \Exception('Không có sản phẩm hợp lệ trong giỏ hàng');
            }

            $appliedCouponsData = session()->get('coupons', []);
            $couponSummaryArray = [];
            $couponTotalDiscount = 0; // Initialize total discount for the new field

            foreach ($appliedCouponsData as $couponData) {
                // Accumulate coupon summary data
                $couponSummaryArray[] = [
                    'code' => $couponData['code'],
                    'discount_value' => $couponData['discount'],
                    'type' => $couponData['type']
                ];
                // Accumulate discount value (already done by $discount variable, but re-calculate here for clarity)
            }
            $couponSummaryJson = json_encode($couponSummaryArray);
            
            // Calculate total discount from coupons as before
            $discount = 0;
            foreach ($appliedCouponsData as $coupon) {
                $discount += ($coupon['type'] === 'percent')
                    ? round($total * $coupon['discount'] / 100)
                    : round($coupon['discount']);
            }
            $couponTotalDiscount = $discount; // Assign the calculated discount to the new field

            Log::info('Applied coupons', [
                'coupons' => $appliedCouponsData,
                'discount' => $discount,
                'coupon_summary_json' => $couponSummaryJson,
                'coupon_total_discount' => $couponTotalDiscount
            ]);

            $total = max(0, round($total - $discount)); // $total here is subtotal - discount, before shipping

            $selectedAddress = Address::find($request->district);
            if (!$selectedAddress) {
                throw new \Exception('Địa chỉ không hợp lệ.');
            }
            $shippingFee = $selectedAddress->shipping_fee;
            $districtName = $selectedAddress->name;
            $total += $shippingFee; // Final total after shipping

            Log::debug('Shipping Fee Info', [
                'selected_district_id' => $request->district,
                'district_name' => $districtName,
                'shipping_fee' => $shippingFee,
                'total_with_shipping' => $total,
            ]);

            if ($request->payment_method === 'banking') {
                session([
                    'vnp_order' => [
                        'name' => $request->name,
                        'phone' => $request->phone_raw, 
                        'address_id' => $selectedAddress->id,
                        'address_detail' => $request->address_detail,
                        'district_name' => $districtName,
                        'total' => $total,
                        'details' => $orderDetails,
                        'discount' => $discount, // This is coupon_total_discount
                        'shipping_fee' => $shippingFee,
                        'user_id' => Auth::check() ? Auth::id() : null,
                        'note' => $request->note ?? null,
                        'status' => 'pending_payment',
                        'coupon_summary' => $couponSummaryJson, // ADDED
                        'coupon_total_discount' => $couponTotalDiscount, // ADDED
                    ]
                ]);
                Log::info('Stored vnp_order in session', [
                    'vnp_order' => session()->get('vnp_order')
                ]);

                DB::commit();
                return redirect()->route('vnpay.redirect');
            }

            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : null;
            $order->name = $request->name;
            $order->phone = $request->phone_raw; 
            $order->address_id = $selectedAddress->id;
            $order->address_detail = $request->address_detail;
            $order->district_name = $districtName;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->shipping_fee = $shippingFee;
            $order->total = $total;
            $order->coupon_summary = $couponSummaryJson; // ADDED
            $order->coupon_total_discount = $couponTotalDiscount; // ADDED

            if (!$order->save()) {
                Log::error('Failed to save order', [
                    'order_data' => $order->toArray()
                ]);
                throw new \Exception('Không thể lưu đơn hàng');
            }
            Log::info('Order saved', [
                'order_id' => $order->id,
                'order_data' => $order->toArray()
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
                $orderDetail->note = $detail['note'] ?? null;
                $orderDetail->status = $detail['status'] ?? 'pending';

                if (!$orderDetail->save()) {
                    Log::error('Failed to save order detail', [
                        'order_id' => $order->id,
                        'detail_data' => $detail
                    ]);
                    throw new \Exception('Không thể lưu chi tiết đơn hàng');
                }
                Log::debug('Order detail saved', [
                    'order_id' => $order->id,
                    'order_detail' => $orderDetail->toArray()
                ]);
            }

            // Save applied coupons to coupon_order pivot table and increment usage
            if (!empty($appliedCouponsData)) {
                foreach ($appliedCouponsData as $couponData) {
                    $couponModel = Coupon::where('code', $couponData['code'])->first();
                    if ($couponModel) {
                        // Attach coupon to order via the pivot table
                        // This assumes you have a many-to-many relationship defined in your Order model:
                        // public function coupons() { return $this->belongsToMany(Coupon::class, 'coupon_order'); }
                        $order->coupons()->attach($couponModel->id);
                        Log::debug('Attached coupon to order', [
                            'order_id' => $order->id,
                            'coupon_id' => $couponModel->id,
                            'coupon_code' => $couponModel->code
                        ]);

                        // Increment used count for the coupon
                        $couponModel->increment('used');
                        Log::debug('Incremented coupon usage', [
                            'coupon_id' => $couponModel->id,
                            'new_used_count' => $couponModel->used
                        ]);
                    } else {
                        Log::warning('Applied coupon not found in DB during order processing (code: ' . $couponData['code'] . ')');
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
            Log::info('Session cleared', [
                'cart' => session()->get('cart'),
                'coupons' => session()->get('coupons')
            ]);

            DB::commit();
            return redirect()->route('order.complete', $order->id)
                ->with('success', 'Đặt hàng thành công!')
                ->with('order_number', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'session_cart' => session()->get('cart', [])
            ]);

            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success($orderId)
    {
        try {
            $order = Order::with('orderDetails.size', 'orderDetails.product')->findOrFail($orderId);
            $allToppings = Product_topping::all()->keyBy('id');
            Log::info('All Toppings', ['toppings' => $allToppings->toArray()]);
            return view('client.order-complete', compact('order', 'allToppings'));
        } catch (\Exception $e) {
            Log::error('Order Complete Page Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
    }
}