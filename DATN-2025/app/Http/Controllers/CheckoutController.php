<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\sanpham;
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

        session()->forget('_old_input');
        
        return view('client.checkout', compact('items', 'cart'));
    }

    public function process(Request $request)
    {
        try {
            Log::info('Starting checkout process', [
                'is_logged_in' => Auth::check(),
                'request_data' => $request->all()
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'payment_method' => 'required|in:cash,banking,momo',
                'terms' => 'required|accepted'
            ]);

            DB::beginTransaction();

            $total = 0;
            $orderDetails = [];
            
            if (Auth::check()) {
                Log::info('Processing logged-in user checkout');
                
                $userCart = Cart::where('user_id', Auth::id())->first();
                if (!$userCart) {
                    throw new \Exception('Giỏ hàng không tồn tại');
                }

                $cartDetails = Cartdetail::with(['product', 'size'])
                    ->where('cart_id', $userCart->id)
                    ->get();

                foreach ($cartDetails as $item) {
                    if (!$item->product) {
                        Log::warning('Product not found for cart item', ['item' => $item]);
                        continue;
                    }
                    
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
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'total' => $itemTotal
                    ];
                }
            } else {
                Log::info('Processing guest checkout');
                
                $sessionCart = session()->get('cart', []);
                Log::info('Processing guest cart', ['cart_data' => $sessionCart]);
                
                if (empty($sessionCart)) {
                    throw new \Exception('Giỏ hàng trống');
                }

                foreach ($sessionCart as $cartKey => $cartItem) {
                    $product = DB::table('sanphams')
                        ->select(['id', 'name'])
                        ->where('id', $cartItem['sanpham_id'])
                        ->first();

                    if (!$product) {
                        Log::error('Không tìm thấy sản phẩm', ['product_id' => $cartItem['sanpham_id']]);
                        continue;
                    }

                    $basePrice = DB::table('product_attributes')
                        ->where('product_id', $product->id)
                        ->where('id', $cartItem['size_id'])
                        ->value('price') ?? 0;

                    $sizePrice = floatval($cartItem['size_price'] ?? 0);
                    $toppingTotal = 0;
                    
                    if (isset($cartItem['topping_prices']) && is_array($cartItem['topping_prices'])) {
                        $toppingTotal = array_sum(array_map('floatval', $cartItem['topping_prices']));
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
            }

            $coupons = session()->get('coupons', []);
            $discount = 0;
            foreach ($coupons as $coupon) {
                $discount += ($coupon['type'] === 'percent') 
                    ? ($total * $coupon['discount'] / 100) 
                    : $coupon['discount'];
            }
            
            $total = max(0, $total - $discount);

            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : null;
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

            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if ($userCart) {
                    Cartdetail::where('cart_id', $userCart->id)->delete();
                    $userCart->delete();
                }
            }

            session()->forget(['cart', 'coupons', '_old_input']);

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
            
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function success($orderId)
    {
        try {
            $order = Order::with('details')->findOrFail($orderId);
            return view('client.order-complete', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order Complete Page Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng');
        }
    }

}
