<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\OrderDetail;
use App\Models\sanpham;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function reorder($orderId)
    {
        $orderDetails = OrderDetail::where('order_id', $orderId)->get();

        if ($orderDetails->isEmpty()) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        foreach ($orderDetails as $item) {
            $cartItem = [
                'product_id' => $item->product_id,
                'size_id' => $item->size_id,
                'topping_id' => $item->topping_id,
                'quantity' => $item->quantity,
            ];

            if (Auth::check()) {
                $cart = Cart::firstOrCreate([
                    'user_id' => Auth::id()
                ]);

                Cartdetail::updateOrCreate(
                    [
                        'cart_id' => $cart->id,
                        'product_id' => $cartItem['product_id'],
                        'size_id' => $cartItem['size_id'],
                        'topping_id' => $cartItem['topping_id'],
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$cartItem['quantity']}"),
                    ]
                );
            } else {
                $sessionCart = Session::get('cart', []);

                $key = $cartItem['product_id'] . '-' . $cartItem['size_id'] . '-' . $cartItem['topping_id'];

                if (isset($sessionCart[$key])) {
                    $sessionCart[$key]['quantity'] += $cartItem['quantity'];
                } else {
                    $product = sanpham::find($cartItem['product_id']);
                    $sessionCart[$key] = [
                        'product_id' => $cartItem['product_id'],
                        'size_id' => $cartItem['size_id'],
                        'topping_id' => $cartItem['topping_id'],
                        'quantity' => $cartItem['quantity'],
                        'product_name' => $product->name ?? 'Sản phẩm',
                        'product_price' => $product->price ?? 0,
                        'image' => $product->image ?? '',
                    ];
                }

                Session::put('cart', $sessionCart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Toàn bộ sản phẩm đã được thêm vào giỏ hàng.');
    }
}
