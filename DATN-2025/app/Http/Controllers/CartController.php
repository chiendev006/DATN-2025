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
use App\Models\Coupon;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

            foreach ($items as $item) {
                if (!$item->product) continue;

                $size = $item->size;
                $basePrice = $size ? $size->price : $item->product->price;

                $toppingPrice = 0;
                if (!empty($item->topping_id)) {
                    $toppingIdString = (string) $item->topping_id;
                    $toppingIds = array_filter(array_map('trim', explode(',', $toppingIdString)));
                    if (!empty($toppingIds)) {
                        $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                    }
                }

                $subtotal += ($basePrice + $toppingPrice) * $item->quantity;
            }
        } else {
            $cartSession = session('cart', []);
            foreach ($cartSession as $key => $item) {
                $items->push((object) $item);

                $basePrice = $item['size_price'] ?? 0;
                $toppingPrice = 0;

                if (isset($item['topping_ids'])) {
                    $sessionToppingIdsString = (string) $item['topping_ids'];
                    if ($sessionToppingIdsString !== '') {
                        $sessionToppingIdsArray = array_map('intval', array_filter(array_map('trim', explode(',', $sessionToppingIdsString))));

                        if (!empty($sessionToppingIdsArray)) {
                            $toppingPrice = Product_topping::whereIn('id', $sessionToppingIdsArray)->sum('price');
                        }
                    }
                }
                $subtotal += ($basePrice + $toppingPrice) * ($item['quantity'] ?? 1);
            }
        }

        $this->applyCouponsToCart($subtotal, $discount, $coupons);

        $total = max(0, $subtotal - $discount);

        $now = now();

        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->get()
            ->filter(function ($coupon) {
                return is_null($coupon->usage_limit) || $coupon->used < $coupon->usage_limit;
            });

        $expiredCoupons = \App\Models\Coupon::where(function ($q) use ($now) {
                $q->where('expires_at', '<', $now)
                  ->orWhere('is_active', false);
            })
            ->orWhere(function ($q) {
                $q->whereNotNull('usage_limit')
                  ->whereColumn('used', '>=', 'usage_limit');
            })
            ->get();

        return view('client.cart', compact(
            'items',
            'subtotal',
            'discount',
            'total',
            'coupons',
            'availableCoupons',
            'expiredCoupons'
        ));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function addToCart(Request $request, $id)
    {
        $sanpham = Sanpham::findOrFail($id);

        $sizeId = $request->input('size_id');
        $size = Size::find($sizeId);

        $toppingIds = $request->input('topping_ids', []);
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);
        $toppingIdsString = implode(',', $toppingIds);
        $productToppings = Product_topping::whereIn('id', $toppingIds)->get();
        $qty = max(1, (int)$request->input('qty', 1));
        $basePrice = $size ? $size->price : ($sanpham->price ?? 0);
        $toppingPrice = $productToppings->sum('price');
        $unitPrice = $basePrice + $toppingPrice;
        $key = $sanpham->id . '-' . $sizeId . '-' . $toppingIdsString;
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );

            $existingItem = Cartdetail::where('cart_id', $cart->id)
                ->where('product_id', $sanpham->id)
                ->where('size_id', $sizeId)
                ->where('topping_id', $toppingIdsString)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $qty;
                $existingItem->save();
            } else {
                $cartDetail = new Cartdetail([
                    'cart_id' => $cart->id,
                    'product_id' => $sanpham->id,
                    'size_id' => $sizeId,
                    'topping_id' => $toppingIdsString,
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
                'topping_ids'   => $toppingIdsString,
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

        return redirect()->route('shop.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     * Make a unique key for cart items.
     * @param int $productId
     * @param int $sizeId
     * @param array $toppingIds
     * @return string
     */
    private function _makeCartKey($productId, $sizeId, $toppingIds = [])
    {
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);
        return implode('-', [$productId, $sizeId, implode(',', $toppingIds)]);
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCart(Request $request)
    {
        try {
            $key = $request->input('key');
            $newQuantity = max(1, (int)$request->input('quantity', 1));
            $productId = $request->input('product_id');
            $sizeId = $request->input('size_id');
            $toppingIds = $request->input('topping_ids', []);

            $toppingIds = array_map('intval', (array)$toppingIds);
            sort($toppingIds);
            $toppingIdsString = implode(',', $toppingIds);

            if (!$key && $productId && $sizeId !== null) {
                $key = $this->_makeCartKey($productId, $sizeId, $toppingIds);
            }

            $subtotal = 0;
            $lineTotal = 0; 

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

                if (!$cartDetail) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }

                $cartDetail->quantity = $newQuantity;
                $cartDetail->save();

                $subtotal = $this->_updateCartTotal($cart); 

                $product = $cartDetail->product;
                $size = $cartDetail->size;
                $toppingPrice = 0;
                if (!empty($cartDetail->topping_id)) {
                    $toppingIdsArr = explode(',', $cartDetail->topping_id);
                    $toppingPrice = Product_topping::whereIn('id', $toppingIdsArr)->sum('price');
                }
                $unitPrice = ($size ? $size->price : ($product->price ?? 0)) + $toppingPrice;
                $lineTotal = $unitPrice * $cartDetail->quantity;

            } else {
                $cartSession = session('cart', []);
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
                $lineTotal = $cartSession[$key]['price'];
            }

            $coupons = session('coupons', []);
            $discount = 0;
            $updatedCoupons = $this->applyCouponsToCart($subtotal, $discount, $coupons); // Re-evaluate and update coupons

            $total = max(0, $subtotal - $discount);

            if (Auth::check()) {
                $cart->total = $total;
                $cart->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng sản phẩm.',
                'key' => $key,
                'quantity' => $newQuantity,
                'line_total' => $lineTotal,
                'subtotal' => $subtotal,
                'discount' => $discount, 
                'total' => $total,
                'applied_coupons' => $updatedCoupons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật giỏ hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $key
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request, $key = null)
    {
        try {
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

            $subtotal = 0; 

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
                    $subtotal = $this->_updateCartTotal($cart); 
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
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
            }

            $coupons = session('coupons', []);
            $discount = 0;
            $updatedCoupons = $this->applyCouponsToCart($subtotal, $discount, $coupons); 

            $total = max(0, $subtotal - $discount);

            if (Auth::check()) {
                $cart->total = $total;
                $cart->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'key' => $key,
                'subtotal' => $subtotal,
                'discount' => $discount, 
                'total' => $total,
                'applied_coupons' => $updatedCoupons
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the total price of the cart.
     * @param Cart $cart
     * @return float The calculated subtotal (before discount)
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

    /**
     * Helper to apply coupons and calculate discount, also removes invalid coupons.
     *
     * @param float $subtotal
     * @param float $discount (passed by reference, will be updated with total discount)
     * @param array $currentCoupons (session coupons data)
     * @return array The updated list of applied coupons (only valid ones)
     */
    private function applyCouponsToCart(float $subtotal, float &$discount, array $currentCoupons)
    {
        $discount = 0; 
        $updatedCoupons = [];
        $now = now();

        foreach ($currentCoupons as $code => $couponData) {
            $coupon = Coupon::where('code', $code)
                ->where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                })
                ->first();

            $isValid = true;
            if (!$coupon) {
                $isValid = false; 
            } elseif ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
                $isValid = false; 
            } elseif ($coupon->user_id && Auth::check() && Auth::id() !== $coupon->user_id) {
                $isValid = false; 
            } elseif ($coupon->user_id && !Auth::check()) {
                $isValid = false; 
            } elseif ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                $isValid = false; 
            }

            if ($isValid) {
                if ($coupon->type === 'percent') {
                    $discount += ($subtotal * $coupon->discount) / 100;
                } elseif ($coupon->type === 'fixed') {
                    $discount += $coupon->discount;
                }
                $updatedCoupons[$coupon->code] = [
                    'code' => $coupon->code,
                    'discount' => $coupon->discount,
                    'type' => $coupon->type
                ];
            }
        }
        session(['coupons' => $updatedCoupons]);
        return $updatedCoupons;
    }


    /**
     * Apply a coupon to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon(Request $request)
    {
        $code = $request->input('code');
        $cartSubtotal = $request->input('subtotal', 0); 

        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã không hợp lệ hoặc đã hết hạn.']);
        }

        if ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã đã hết lượt sử dụng.']);
        }

        if (Auth::check()) {
            if ($coupon->user_id && Auth::id() !== $coupon->user_id) {
                return response()->json(['success' => false, 'message' => 'Mã này không dành cho bạn.']);
            }
        } else {
            if ($coupon->user_id) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để dùng mã này.']);
            }
        }

        if ($coupon->min_order_value && $cartSubtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng cần tối thiểu ' . number_format($coupon->min_order_value) . 'đ để áp dụng mã.',
                'subtotal' => $cartSubtotal 
            ]);
        }

        $couponData = [
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'type' => $coupon->type
        ];

        $coupons = session('coupons', []);
        $coupons[$coupon->code] = $couponData;
        session(['coupons' => $coupons]);

        $discount = 0;
        $actualSubtotal = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $actualSubtotal = $this->_updateCartTotal($cart); 
            }
        } else {
            $cartSession = session('cart', []);
            $actualSubtotal = collect($cartSession)->sum(function($item) {
                $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                return $unitPrice * $item['quantity'];
            });
        }
        $updatedCoupons = $this->applyCouponsToCart($actualSubtotal, $discount, $coupons);

        $total = max(0, $actualSubtotal - $discount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công.',
            'coupon' => $couponData,
            'subtotal' => $actualSubtotal, 
            'discount' => $discount,     
            'total' => $total,           
            'applied_coupons' => $updatedCoupons 
        ]);
    }

    /**
     * Remove a specific coupon from the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeCoupon(Request $request)
    {
        $code = $request->input('code');
        $coupons = session('coupons', []);

        if (isset($coupons[$code])) {
            unset($coupons[$code]);
            session(['coupons' => $coupons]);
            $subtotal = 0;
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $subtotal = $this->_updateCartTotal($cart); 
                }
            } else {
                $cartSession = session('cart', []);
                $subtotal = collect($cartSession)->sum(function($item) {
                    $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_prices'] ?? []);
                    return $unitPrice * $item['quantity'];
                });
            }

            $discount = 0;
            $updatedCoupons = $this->applyCouponsToCart($subtotal, $discount, $coupons); 
            $total = max(0, $subtotal - $discount);

            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được gỡ bỏ.',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'applied_coupons' => $updatedCoupons 
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Mã giảm giá không tìm thấy trong giỏ hàng.']);
    }
}