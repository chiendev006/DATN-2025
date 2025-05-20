<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sanpham;
use App\Models\Size;
use App\Models\Topping;
use App\Models\Cart;
use App\Models\Cartdetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
class CartController extends Controller
{
public function addToCart(Request $request, $id)
{
    $product = sanpham::findOrFail($id);
    $qty = $request->qty ?? 1;
    $size = Size::find($request->size_id);
    $topping_ids = $request->topping_ids ?? [];
    $topping_ids_sorted = collect($topping_ids)->sort()->values()->toArray(); 
    $topping_key = implode(',', $topping_ids_sorted); 
    $toppings = Topping::whereIn('id', $topping_ids_sorted)->get();

    $unitPrice = $product->price + ($size->price ?? 0);
    foreach ($toppings as $topping) {
        $unitPrice += $topping->price;
    }
    $totalPrice = $unitPrice * $qty;

    if (Auth::check()) {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $existing = Cartdetail::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('size_id', $size?->id)
            ->where('topping_id', $topping_key)
            ->first();

        if ($existing) {
            $existing->quantity += $qty;
            $existing->save();
        } else {
            Cartdetail::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'size_id' => $size?->id,
                'topping_id' => $topping_key,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng (database)');
    }

    $itemKey = $product->id . '-' . ($size->id ?? 0) . '-' . str_replace(',', '-', $topping_key);
    $cart = session()->get('cart', []);

    if (isset($cart[$itemKey])) {
        $cart[$itemKey]['quantity'] += $qty;
        $cart[$itemKey]['price'] = $cart[$itemKey]['unit_price'] * $cart[$itemKey]['quantity'];
    } else {
        $cart[$itemKey] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'size_id' => $size?->id,
            'size_name' => $size?->name,
            'topping_ids' => $topping_ids_sorted,
            'toppings' => $toppings->map(fn($t) => ['name' => $t->name, 'price' => $t->price])->toArray(),
            'quantity' => $qty,
            'unit_price' => $unitPrice, 
            'price' => $totalPrice,
        ];
    }

    session(['cart' => $cart]);
    return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng (session)');
}

    public function index(){
        if (Auth::check()) {
            $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
            $items = $cart?->items ?? [];
            return view('client.cart', compact('items'));
        }

        $cart = session('cart', []);
        return view('client.cart', compact('cart'));
    }
    public function updateCart(Request $request){
    if (Auth::check()) {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) return redirect()->back();
        $items = $cart->items;
        foreach (['increase', 'decrease'] as $action) {
            if ($request->has($action)) {
                $id = $request->$action;
                $item = $items->find($id);
                if ($item) {
                    if ($action === 'increase') {
                        $item->quantity++;
                    } elseif ($item->quantity > 1) {
                        $item->quantity--;
                    }
                    $item->save();
                }
            }
        }
        if ($request->has('quantities')) {
            foreach ($request->input('quantities') as $id => $quantity) {
                $item = $items->find($id);
                if ($item && is_numeric($quantity) && $quantity > 0) {
                    $item->quantity = intval($quantity);
                    $item->save();
                }
            }
        }
        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }
    $cart = session('cart', []);
    foreach (['increase', 'decrease'] as $action) {
        if ($request->has($action)) {
            $key = $request->$action;
            if (isset($cart[$key])) {
                if ($action === 'increase') {
                    $cart[$key]['quantity']++;
                } elseif ($cart[$key]['quantity'] > 1) {
                    $cart[$key]['quantity']--;
                }

                if (!isset($cart[$key]['unit_price'])) {
                    $cart[$key]['unit_price'] = $cart[$key]['price'] / ($cart[$key]['quantity'] ?: 1);
                }
                $cart[$key]['price'] = $cart[$key]['unit_price'] * $cart[$key]['quantity'];
            }
        }
    }
    if ($request->has('quantities')) {
        foreach ($request->input('quantities') as $key => $quantity) {
            if (isset($cart[$key]) && is_numeric($quantity) && $quantity > 0) {
                $cart[$key]['quantity'] = intval($quantity);
                if (!isset($cart[$key]['unit_price'])) {
                    $cart[$key]['unit_price'] = $cart[$key]['price'] / ($cart[$key]['quantity'] ?: 1);
                }
                $cart[$key]['price'] = $cart[$key]['unit_price'] * $cart[$key]['quantity'];
            }
        }
    }
    session(['cart' => $cart]);
    return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
}

    public function removeItem($key){
    if (Auth::check()) {
        $item = Cartdetail::find($key);
    if ($item && $item->cart && $item->cart->user_id === Auth::id()) {
            $item->delete();
        }
    } else {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);
    }
    return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
}
public function boot()
{
    View::composer('*', function ($view) {
        $cartCount = 0;

        if (Auth::check()) {
            $cart = Cart::with('items')->where('user_id', Auth::id())->first();
            $cartCount = $cart?->items->sum('quantity') ?? 0;
        } else {
            $cart = Session::get('cart', []);
            $cartCount = collect($cart)->sum('quantity');
        }
        $view->with('cartCount', $cartCount);
    });
}

}
