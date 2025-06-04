<?php

namespace App\Http\Controllers;

use App\Models\Orderdetail;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyaccountController extends Controller
{
public function index()
{
    $userId = auth()->id();

    $orders = OrderDetail::with(['order', 'product'])->whereHas('order', function ($q) use ($userId) {
        $q->where('user_id', $userId);
    })->get();

    // Get all toppings indexed by ID
    $toppings = Topping::all()->keyBy('id');

    return view('client.myaccount', compact('orders', 'toppings'));
}
}
