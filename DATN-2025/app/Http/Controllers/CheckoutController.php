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
use Illuminate\Support\Str;

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
                'payment_method' => 'required|in:cash,banking',
                'terms' => 'required|accepted'
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
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'total' => $itemTotal,
                        'size_id' => $item->size_id ?? null,
                        'topping_id' => $item->topping_id ?? null,
                        'note' => $request->note ?? null,
                        'status' => 'pending',
                    ];
                }
            } else {
                $sessionCart = session()->get('cart', []);
                if (empty($sessionCart)) {
                    throw new \Exception('Giỏ hàng trống');
                }

                foreach ($sessionCart as $cartItem) {
                    $product = DB::table('sanphams')
                        ->select(['id', 'name'])
                        ->where('id', $cartItem['sanpham_id'])
                        ->first();

                    if (!$product) continue;

                    $basePrice = DB::table('product_attributes')
                        ->where('product_id', $product->id)
                        ->where('id', $cartItem['size_id'])
                        ->value('price') ?? 0;

                    $toppingTotal = isset($cartItem['topping_prices']) && is_array($cartItem['topping_prices'])
                        ? array_sum(array_map('floatval', $cartItem['topping_prices']))
                        : 0;

                    $unitPrice = $basePrice + $toppingTotal;
                    $quantity = intval($cartItem['quantity'] ?? 1);
                    $itemTotal = $unitPrice * $quantity;
                    $total += $itemTotal;

                    Log::debug('Item Pricing (Guest)', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'base_price' => $basePrice,
                        'topping_total' => $toppingTotal,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'item_total' => $itemTotal
                    ]);

                    $orderDetails[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $itemTotal,
                        'size_id' => $cartItem['size_id'] ?? null,
                        'topping_id' => !empty($cartItem['topping_ids']) ? implode(',', $cartItem['topping_ids']) : null,
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

            if ($request->payment_method === 'banking') {
                DB::commit();

                Log::debug('VNPay Session Total', [
                    'total' => $total,
                    'discount' => $discount,
                    'details' => $orderDetails
                ]);

                session([
                    'vnp_order' => [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'total' => $total,
                    'details' => $orderDetails,
                    'discount' => $discount,
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'note' => $request->note ?? null,
                    'status' => 'completed',
                    ]
                ]);

                return redirect()->route('vnpay.redirect');
            }

            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : null;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->total = $total;

            // Sinh transaction_id duy nhất
            do {
                $transactionId = date('YmdHis') . (Auth::check() ? Auth::id() : '0') . Str::random(6);
            } while (Order::where('transaction_id', $transactionId)->exists());

            $order->transaction_id = $transactionId;

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
            $orderDetail->topping_id = $detail['topping_id'] ?? null;
            $orderDetail->note = $detail['note'] ?? null;
            $orderDetail->status = $detail['status'] ?? 'pending';

            if (!$orderDetail->save()) {
                throw new \Exception('Không thể lưu chi tiết đơn hàng');
            }
        }


            if (Auth::check()) {
                $userCart = Cart::where('user_id', Auth::id())->first();
                if ($userCart) {
                    Cartdetail::where('cart_id', $userCart->id)->delete();
                    $userCart->delete();
                }
            }

            session()->forget(['cart', 'coupons', '_old_input']);

            DB::commit();
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
