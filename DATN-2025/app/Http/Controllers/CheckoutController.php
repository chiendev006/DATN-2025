<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cartdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index() {
        if(Auth::check()) {
            $cart_id=
            $cart=Cartdetail::where('cart_id', )->get();
            carrt
                foreach($cart as $item) {

                }
            dd($cart);
        } else {
            $cart= session('cart', []);
        }

    }
}
