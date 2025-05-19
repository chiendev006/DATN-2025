<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sanpham;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product = sanpham::findOrFail($id);
        $qty = $request->qty ?? 1;
        $size = Size::find($request->size_id);
        $topping_ids = $request->topping_ids ?? [];
        $toppings = Topping::whereIn('id', $topping_ids)->get();
        $totalPrice = $product->price + ($size->price ?? 0);
        foreach ($toppings as $topping) {
            $totalPrice += $topping->price;
        }
        $totalPrice *= $qty;
        $toppingKey = implode('-', collect($toppings)->pluck('id')->sort()->toArray());
        $itemKey = $product->id . '-' . ($size->id ?? 0) . '-' . $toppingKey;
        $cart = session()->get('cart', []);
        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $qty;
            $cart[$itemKey]['price'] += $totalPrice;
        } else {
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'size_id' => $size ? $size->name : 'Không rõ',
                'toppings' => $toppings->map(function ($t) {
                    return ['name' => $t->name, 'price' => $t->price];
                })->toArray(),
                'quantity' => $qty,
                'price' => $totalPrice,
            ];
        }
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function index()
    {
        return view('client.cart');
    }
    public function removeItem($key)
{
    $cart = session('cart', []);
    if (isset($cart[$key])) {
        unset($cart[$key]);
        session(['cart' => $cart]);
    }
    return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
}

public function updateCart(Request $request)
{
    $cart = session('cart', []);

    // Tăng số lượng
    if ($request->has('increase')) {
        $key = $request->increase;
        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
            $cart[$key]['price'] = ($cart[$key]['price'] / ($cart[$key]['quantity'] - 1)) * $cart[$key]['quantity'];
        }
    }

    // Giảm số lượng
    if ($request->has('decrease')) {
        $key = $request->decrease;
        if (isset($cart[$key]) && $cart[$key]['quantity'] > 1) {
            $cart[$key]['quantity']--;
            $cart[$key]['price'] = ($cart[$key]['price'] / ($cart[$key]['quantity'] + 1)) * $cart[$key]['quantity'];
        }
    }

    session(['cart' => $cart]);
    return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
}
}

