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

    // Lấy size
    $sizeId = $request->input('size_id');
    $size = Size::find($sizeId);

    // Lấy topping (có thể là mảng giá hoặc id, tùy bạn truyền lên)
    $toppingIds = $request->input('topping_ids', []);
    $toppings = Topping::whereIn('id', (array)$toppingIds)->get();

    $qty = max(1, (int)$request->input('qty', 1));

    // Tính giá
    $basePrice = $size ? $size->price : $sanpham->price;
    $toppingPrice = $toppings->sum('price');
    $totalPrice = ($basePrice + $toppingPrice) * $qty;

    if (Auth::check()) {
        // Đã đăng nhập: lưu vào DB
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['session_id' => null]
        );

        // Tạo cart detail mới
        $cartDetail = new Cartdetail([
            'cart_id' => $cart->id,
            'product_id' => $sanpham->id,
            'size_id' => $sizeId,
            'topping_id' => implode(',', (array)$toppingIds),
            'quantity' => $qty
        ]);
        $cartDetail->save();

    } else {
        // Chưa đăng nhập: lưu vào session
        $cart = session('cart', []);
        // Tạo key duy nhất cho sản phẩm theo id-size-topping
        $key = $sanpham->id . '-' . $sizeId . '-' . implode(',', (array)$toppingIds);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
            $cart[$key]['price'] = ($basePrice + $toppingPrice) * $cart[$key]['quantity'];
        } else {
            $cart[$key] = [
                'sanpham_id'    => $sanpham->id,
                'name'          => $sanpham->name,
                'size_id'       => $sizeId,
                'size_name'     => $size ? $size->size : null,
                'size_price'    => $size ? $size->price : 0, // <-- luôn có giá trị, mặc định 0 nếu không có size
                'topping_ids'   => $toppingIds,
                'topping_names' => $toppings->pluck('name')->toArray(),
                'topping_price' => $toppings->pluck('price')->toArray(),
                'quantity'      => $qty,
                'unit_price'    => $basePrice + $toppingPrice,
                'price'         => $totalPrice,
                'image'         => $sanpham->image,
            ];
        }
        session(['cart' => $cart]);
    }

    return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
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
