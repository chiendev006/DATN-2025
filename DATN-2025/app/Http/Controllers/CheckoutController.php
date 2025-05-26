<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\sanpham;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index() {
        if (Auth::check()) {
            $cartModel = Cart::with('items.product')->where('user_id', Auth::id())->first();
            $items = $cartModel?->items ?? [];
            $cart = [];
        } else {
            $items = [];
            $cart = session('cart', []);
        }
        return view('client.checkout', compact('items', 'cart'));
    }
    
}
