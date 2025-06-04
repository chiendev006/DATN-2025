<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product_topping;

class CartComposer
{
    public function compose(View $view)
    {
        $items = collect([]);
        $subtotal = 0;

        if (Auth::check()) {
            $cart = Cart::with(['cartdetails.product', 'cartdetails.size'])
                        ->where('user_id', Auth::id())
                        ->first();

            if ($cart) {
                $items = $cart->cartdetails;

                foreach ($items as $item) {
                    if (!$item->product) continue;

                    $sizePrice = $item->size->price ?? $item->product->price;
                    $toppingIds = array_filter(explode(',', $item->topping_id ?? ''));
                    $toppingPrice = $toppingIds 
                        ? Product_topping::whereIn('id', $toppingIds)->sum('price') 
                        : 0;

                    $subtotal += ($sizePrice + $toppingPrice) * $item->quantity;
                }
            }
        } else {
            $cartSession = session('cart', []);
            foreach ($cartSession as $item) {
                $items->push((object) $item);
                $basePrice = $item['size_price'] ?? 0;
                $toppingPrice = array_sum($item['topping_prices'] ?? []);
                $subtotal += ($basePrice + $toppingPrice) * ($item['quantity'] ?? 1);
            }
        }

        $view->with(compact('items', 'subtotal'));
    }
}
