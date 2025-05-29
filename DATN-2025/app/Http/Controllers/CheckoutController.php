<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = [];
        $cart = [];
        
        if (Auth::check()) {
            $userCart = Cart::where('user_id', Auth::id())->first();
            if ($userCart) {
                $items = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();
            }
        } else {
            $cart = session()->get('cart', []);
        }

        return view('client.checkout', compact('items', 'cart'));
    }

    public function process(Request $request)
    {
        try {
            Log::info('Starting checkout process', [
                'is_logged_in' => Auth::check(),
                'request_data' => $request->all()
            ]);

            // Validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'payment_method' => 'required|in:cash,banking,momo',
                'terms' => 'required|accepted'
            ]);

            DB::beginTransaction();

            // Calculate total from cart first
            $total = 0;
            $orderDetails = [];
            
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if (!$cart) {
                    throw new \Exception('Giỏ hàng không tồn tại');
                }

                $cartDetails = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $cart->id)
                    ->get();

                foreach ($cartDetails as $item) {
                    if (!$item->product) continue;
                    
                    $productPrice = $item->product->price ?? 0;
                    $sizePrice = $item->size ? ($item->size->price ?? 0) : 0;
                    
                    $toppingPrice = 0;
                    if (!empty($item->topping_id)) {
                        $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id)));
                        if (!empty($toppingIds)) {
                            $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price');
                        }
                    }
                    
                    $unitPrice = $productPrice + $sizePrice + $toppingPrice;
                    $itemTotal = $unitPrice * $item->quantity;
                    $total += $itemTotal;

                    $orderDetails[] = [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'total' => $itemTotal
                    ];
                }
            } else {
                $sessionCart = session()->get('cart', []);
                Log::info('Processing guest cart', ['cart_data' => $sessionCart]);
                
                if (empty($sessionCart)) {
                    throw new \Exception('Giỏ hàng trống');
                }

                foreach ($sessionCart as $cartKey => $cartItem) {
                    $product = DB::table('sanphams')->find($cartItem['sanpham_id']);
                    if (!$product) {
                        Log::error('Product not found', ['product_id' => $cartItem['sanpham_id']]);
                        continue;
                    }

                    Log::info('Processing cart item', [
                        'product' => $product,
                        'cart_item' => $cartItem
                    ]);

                    // Calculate total price including size and toppings
                    $basePrice = $product->price;
                    $sizePrice = floatval($cartItem['size_price'] ?? 0);
                    $toppingTotal = 0;
                    
                    if (isset($cartItem['topping_prices']) && is_array($cartItem['topping_prices'])) {
                        foreach ($cartItem['topping_prices'] as $price) {
                            $toppingTotal += floatval($price);
                        }
                    }

                    $unitPrice = $basePrice + $sizePrice + $toppingTotal;
                    $quantity = intval($cartItem['quantity'] ?? 1);
                    $itemTotal = $unitPrice * $quantity;
                    
                    $total += $itemTotal;

                    $orderDetails[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $itemTotal
                    ];
                }

                Log::info('Calculated order details', [
                    'total' => $total,
                    'order_details' => $orderDetails
                ]);
            }

            // Apply discount if any
            $coupons = session()->get('coupons', []);
            $discount = 0;
            foreach ($coupons as $coupon) {
                $discount += ($coupon['type'] === 'percent') 
                    ? ($total * $coupon['discount'] / 100) 
                    : $coupon['discount'];
            }
            
            $total = max(0, $total - $discount);

            // Create and save order
            $order = new Order();
            $order->user_id = null; // Explicitly set null for guest users
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->total = $total;

            Log::info('Attempting to save order', [
                'order_data' => $order->toArray()
            ]);

            if (!$order->save()) {
                throw new \Exception('Không thể lưu đơn hàng');
            }

            Log::info('Order saved successfully', ['order_id' => $order->id]);

            // Create order details
            foreach ($orderDetails as $detail) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $detail['product_id'];
                $orderDetail->product_name = $detail['product_name'];
                $orderDetail->product_price = $detail['product_price'];
                $orderDetail->quantity = $detail['quantity'];
                $orderDetail->total = $detail['total'];
                
                if (!$orderDetail->save()) {
                    throw new \Exception('Không thể lưu chi tiết đơn hàng');
                }
            }

            Log::info('Order details saved successfully');

            // Clear cart data
            if (Auth::check()) {
                Cartdetail::where('cart_id', $cart->id)->delete();
                $cart->delete();
            } else {
                session()->forget('cart');
            }

            // Clear coupons
            session()->forget('coupons');

            DB::commit();
            Log::info('Checkout completed successfully');

            return redirect()->route('order.complete', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        try {
            $order = Order::with('orderDetails')->findOrFail($orderId);
            return view('client.order-complete', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order Complete Page Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
    }
}
