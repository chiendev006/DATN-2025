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
    $sanpham = Sanpham::findOrFail($id);

    $sizeId = $request->input('size_id');
    $size = Size::find($sizeId);

    $toppingIds = $request->input('topping_ids', []);
    sort($toppingIds); 

    $toppings = Topping::whereIn('id', (array)$toppingIds)->get();

    $qty = max(1, (int)$request->input('qty', 1));

    $basePrice = $size ? $size->price : $sanpham->price;
    $toppingPrice = $toppings->sum('price');
    $unitPrice = $basePrice + $toppingPrice;
    $totalPrice = $unitPrice * $qty;

    $key = $sanpham->id . '-' . $sizeId . '-' . implode(',', $toppingIds);

    if (Auth::check()) {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => null]
        );

        $existingItem = Cartdetail::where('cart_id', $cart->id)
            ->where('product_id', $sanpham->id)
            ->where('size_id', $sizeId)
            ->where('topping_id', implode(',', $toppingIds))
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $qty;
            $existingItem->save();
        } else {
            $cartDetail = new Cartdetail([
                'cart_id' => $cart->id,
                'product_id' => $sanpham->id,
                'size_id' => $sizeId,
                'topping_id' => implode(',', $toppingIds),
                'quantity' => $qty
            ]);
            $cartDetail->save();
        }
    } else {
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
            $cart[$key]['price'] = $unitPrice * $cart[$key]['quantity'];
        } else {
            $cart[$key] = [
                'sanpham_id'    => $sanpham->id,
                'name'          => $sanpham->name,
                'size_id'       => $sizeId,
                'size_name'     => $size ? $size->size : null,
                'size_price'    => $size ? $size->price : 0,
                'topping_ids'   => $toppingIds,
                'quantity'      => $qty,
                'unit_price'    => $unitPrice,
                'price'         => $totalPrice,
                'image'         => $sanpham->image,
                'topping_names' => [],
                'topping_price' => []
            ];

            foreach ($toppingIds as $productToppingId) {
                $productTopping = \App\Models\Product_topping::find($productToppingId);
                $cart[$key]['topping_names'][] = $productTopping ? $productTopping->topping : '';
                $cart[$key]['topping_price'][] = $productTopping ? $productTopping->price : 0;
            }
        }

        session(['cart' => $cart]);
    }

    return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
}


  public function index()
{
    $coupons = session('coupons', []); 
    $discount = 0;
    $subtotal = 0;
    $total = 0;
    $items = [];

    if (Auth::check()) {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        $items = $cart?->items ?? [];

        foreach ($items as $item) {
            $sizePrice = $item->size?->price ?? $item->product->price;
            $toppingPrice = 0;

            if ($item->topping_id) {
                $toppingIds = explode(',', $item->topping_id);
                $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price');
            }

            $subtotal += ($sizePrice + $toppingPrice) * $item->quantity;
        }
    } else {
        $cart = session('cart', []);
        foreach ($cart as $item) {
            $subtotal += $item['price'];
        }
    }

    foreach ($coupons as $coupon) {
        if ($coupon['type'] === 'percent') {
            $discount += ($subtotal * $coupon['discount']) / 100;
        } elseif ($coupon['type'] === 'fixed') {
            $discount += $coupon['discount'];
        }
    }

    $total = max(0, $subtotal - $discount);

    return view('client.cart', compact(
        'cart',
        'items',
        'subtotal',
        'discount',
        'total',
        'coupons'
    ));
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

    if (request()->ajax()) {
        return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!']);
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
      if ($request->ajax()) {
    return response()->json(['success' => true, 'message' => 'Đã cập nhật giỏ hàng!']);
}
    return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
}
}