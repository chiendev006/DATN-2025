<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanpham; 
use App\Models\Size;
use App\Models\Topping; 
use App\Models\Product_topping;
use App\Models\Cart;
use App\Models\Cartdetail;

class CartController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        $coupons = session('coupons', []);
        $discount = 0;
        $subtotal = 0;
        $total = 0;
        $items = collect([]); 

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'total' => 0
                ]);
            }
            
            $cart->load(['cartdetails.product', 'cartdetails.size']);
            $items = $cart->cartdetails;

            if ($items) {
                foreach ($items as $item) {
                    if (!$item->product) continue;

                    $size = $item->size;
                    $basePrice = $size ? $size->price : $item->product->price;
                    
                    $toppingPrice = 0;
                    if (!empty($item->topping_id)) {
                        $toppingIds = array_filter(array_map('trim', explode(',', $item->topping_id)));
                        if (!empty($toppingIds)) {
                            $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                        }
                    }

                    $subtotal += ($basePrice + $toppingPrice) * $item->quantity;
                }
            }
        } else {
            $cartSession = session('cart', []);
            foreach ($cartSession as $key => $item) {
                $items->push((object) $item);
                $basePrice = $item['size_price'] ?? 0;
                $toppingPrice = array_sum($item['topping_prices'] ?? []);
                $subtotal += ($basePrice + $toppingPrice) * ($item['quantity'] ?? 1);
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
            'items',
            'subtotal',
            'discount',
            'total',
            'coupons'
        ));
    }

    /**
     * 
     */
    public function addToCart(Request $request, $id)
    {
        $sanpham = Sanpham::findOrFail($id);

        $sizeId = $request->input('size_id');
        $size = Size::find($sizeId);

        $toppingIds = $request->input('topping_ids', []);
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);

        $productToppings = Product_topping::whereIn('id', $toppingIds)->get();

        $qty = max(1, (int)$request->input('qty', 1));

        $basePrice = $size ? $size->price : ($sanpham->price ?? 0); 
        $toppingPrice = $productToppings->sum('price');
        $unitPrice = $basePrice + $toppingPrice;

        $key = $sanpham->id . '-' . $sizeId . '-' . implode(',', $toppingIds);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null, 'total' => 0] 
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

            $this->_updateCartTotal($cart);

        } else {
            $cartSession = session('cart', []);

            if (isset($cartSession[$key])) {
                $cartSession[$key]['quantity'] += $qty;
                $cartSession[$key]['price'] = $unitPrice * $cartSession[$key]['quantity'];
            } else {
                $cartSession[$key] = [
                    'sanpham_id'    => $sanpham->id,
                    'name'          => $sanpham->name,
                    'size_id'       => $sizeId,
                    'size_name'     => $size ? $size->size : null,
                    'size_price'    => $size ? $size->price : ($sanpham->price ?? 0),
                    'topping_ids'   => $toppingIds,
                    'quantity'      => $qty,
                    'unit_price'    => $unitPrice,
                    'price'         => $unitPrice * $qty, 
                    'image'         => $sanpham->image,
                    'topping_names' => [],
                    'topping_prices' => [] 
                ];

                foreach ($productToppings as $productTopping) {
                    $cartSession[$key]['topping_names'][] = $productTopping->topping;
                    $cartSession[$key]['topping_prices'][] = $productTopping->price;
                }
            }

            session(['cart' => $cartSession]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     */
    private function _makeCartKey($productId, $sizeId, $toppingIds = [])
    {
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);
        return implode('-', [$productId, $sizeId, implode(',', $toppingIds)]);
    }

    /**
     */
    public function updateCart(Request $request)
    {
        \Log::info('Update Cart Request:', $request->all());
        
        try {
            $key = $request->input('key');
            $newQuantity = max(1, (int)$request->input('quantity', 1));
            $productId = $request->input('product_id');
            $sizeId = $request->input('size_id');
            $toppingIds = $request->input('topping_ids', []);
            
            $toppingIds = array_map('intval', (array)$toppingIds);
            sort($toppingIds);
            $toppingIdsString = implode(',', $toppingIds);

            \Log::info('Normalized Data:', [
                'key' => $key,
                'productId' => $productId,
                'sizeId' => $sizeId,
                'toppingIds' => $toppingIds,
                'toppingIdsString' => $toppingIdsString
            ]);

            if (!$key && $productId && $sizeId !== null) {
                $key = $this->_makeCartKey($productId, $sizeId, $toppingIds);
                \Log::info('Generated Key:', ['key' => $key]);
            }

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                \Log::info('Found Cart:', ['cart_id' => $cart?->id]);

                if (!$cart) {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại.']);
                }

                $cartDetail = null;
                if ($productId && $sizeId !== null) {
                    $cartDetail = Cartdetail::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->where('size_id', $sizeId)
                        ->where('topping_id', $toppingIdsString)
                        ->first();
                    \Log::info('Finding CartDetail by IDs:', [
                        'found' => !!$cartDetail,
                        'cart_id' => $cart->id,
                        'product_id' => $productId,
                        'size_id' => $sizeId,
                        'topping_id' => $toppingIdsString
                    ]);
                } else if ($key) {
                    $parts = explode('-', $key);
                    $pid = $parts[0] ?? null;
                    $sid = $parts[1] ?? null;
                    $tids = isset($parts[2]) ? explode(',', $parts[2]) : [];
                    sort($tids);
                    $tidsString = implode(',', $tids);
                    
                    $cartDetail = Cartdetail::where('cart_id', $cart->id)
                        ->where('product_id', $pid)
                        ->where('size_id', $sid)
                        ->where('topping_id', $tidsString)
                        ->first();
                    \Log::info('Finding CartDetail by Key:', [
                        'found' => !!$cartDetail,
                        'key_parts' => ['pid' => $pid, 'sid' => $sid, 'tids' => $tidsString],
                        'cart_id' => $cart->id
                    ]);
                }

                if (!$cartDetail) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }

                $cartDetail->quantity = $newQuantity;
                $cartDetail->save();
                
                $this->_updateCartTotal($cart);
                $subtotal = $cart->total;
                $total = $cart->total;

                $product = $cartDetail->product;
                $size = $cartDetail->size;
                $toppingPrice = 0;
                if (!empty($cartDetail->topping_id)) {
                    $toppingIdsArr = explode(',', $cartDetail->topping_id);
                    $toppingPrice = Product_topping::whereIn('id', $toppingIdsArr)->sum('price');
                }
                $unitPrice = ($size ? $size->price : ($product->price ?? 0)) + $toppingPrice;
                $lineTotal = $unitPrice * $cartDetail->quantity;

                \Log::info('Updated CartDetail:', [
                    'quantity' => $cartDetail->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật số lượng sản phẩm.',
                    'key' => $key,
                    'quantity' => $cartDetail->quantity,
                    'line_total' => $lineTotal,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            } else {
                $cartSession = session('cart', []);
                \Log::info('Guest Cart Session:', ['key_exists' => isset($cartSession[$key])]);

                if (!isset($cartSession[$key])) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
                $sanpham = Sanpham::find($cartSession[$key]['sanpham_id']);
                $size = Size::find($cartSession[$key]['size_id']);
                $productToppings = Product_topping::whereIn('id', (array)$cartSession[$key]['topping_ids'])->get();
                
                $basePrice = $size ? $size->price : ($sanpham->price ?? 0);
                $toppingPrice = $productToppings->sum('price');
                $unitPrice = $basePrice + $toppingPrice;
                
                $cartSession[$key]['quantity'] = $newQuantity;
                $cartSession[$key]['price'] = $unitPrice * $newQuantity;
                
                session(['cart' => $cartSession]);
                
                $subtotal = collect($cartSession)->sum(function($item) {
                    $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                    return $unitPrice * $item['quantity'];
                });
                
                $coupons = session('coupons', []);
                $discount = 0;
                foreach ($coupons as $c) {
                    $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
                }
                $total = max(0, $subtotal - $discount);

                \Log::info('Updated Guest Cart:', [
                    'key' => $key,
                    'quantity' => $cartSession[$key]['quantity'],
                    'price' => $cartSession[$key]['price'],
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật số lượng sản phẩm.',
                    'key' => $key,
                    'quantity' => $cartSession[$key]['quantity'],
                    'line_total' => $cartSession[$key]['price'],
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Cart Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật giỏ hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     */
    public function removeItem(Request $request, $key = null)
    {
        try {
            \Log::info('Remove Cart Item Request:', [
                'key' => $key,
                'request_data' => $request->all()
            ]);

            $key = $key ?? $request->input('key');
            $productId = $request->input('product_id');
            $sizeId = $request->input('size_id');
            $toppingIds = $request->input('topping_ids', []);

            if (!$key && $productId && $sizeId !== null) {
                $key = $this->_makeCartKey($productId, $sizeId, $toppingIds);
            }

            $toppingIds = array_map('intval', (array)$toppingIds);
            sort($toppingIds);
            $toppingIdsString = implode(',', $toppingIds);

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if (!$cart) {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại.']);
                }

                $cartDetail = null;
                if ($productId && $sizeId !== null) {
                    $cartDetail = Cartdetail::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->where('size_id', $sizeId)
                        ->where('topping_id', $toppingIdsString)
                        ->first();
                } else if ($key) {
                    $parts = explode('-', $key);
                    $pid = $parts[0] ?? null;
                    $sid = $parts[1] ?? null;
                    $tids = isset($parts[2]) ? explode(',', $parts[2]) : [];
                    sort($tids);
                    $tidsString = implode(',', $tids);
                    
                    $cartDetail = Cartdetail::where('cart_id', $cart->id)
                        ->where('product_id', $pid)
                        ->where('size_id', $sid)
                        ->where('topping_id', $tidsString)
                        ->first();
                }

                if ($cartDetail) {
                    $cartDetail->delete();
                    $this->_updateCartTotal($cart);
                    $subtotal = $cart->total;
                    $total = $cart->total;

                    \Log::info('Removed Cart Item:', [
                        'cart_id' => $cart->id,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                        'key' => $key,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
            } else {
                $cartSession = session('cart', []);
                if (isset($cartSession[$key])) {
                    unset($cartSession[$key]);
                    session(['cart' => $cartSession]);

                    $subtotal = collect($cartSession)->sum(function($item) {
                        $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                        return $unitPrice * $item['quantity'];
                    });
                    
                    $coupons = session('coupons', []);
                    $discount = 0;
                    foreach ($coupons as $c) {
                        $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
                    }
                    $total = max(0, $subtotal - $discount);

                    \Log::info('Removed Guest Cart Item:', [
                        'key' => $key,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                        'key' => $key,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Remove Cart Item Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     */
    private function _updateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart->cartdetails as $detail) {
            if (!$detail->product) continue;

            $size = $detail->size;
            $basePrice = $size ? $size->price : $detail->product->price;
            
            $toppingPrice = 0;
            if (!empty($detail->topping_id)) {
                $toppingIds = array_filter(array_map('trim', explode(',', $detail->topping_id)));
                if (!empty($toppingIds)) {
                    $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                }
            }
            
            $total += ($basePrice + $toppingPrice) * $detail->quantity;
        }
        
        $cart->total = $total;
        $cart->save();
        
        return $total;
    }
}
