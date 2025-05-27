<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanpham; // Assuming this is your Product model
use App\Models\Size;
use App\Models\Topping; // Assuming this is your main Topping model
use App\Models\Product_topping; // Assuming this is your model for topping details (e.g., topping price)
use App\Models\Cart;
use App\Models\Cartdetail;

class CartController extends Controller
{
    /**
     * Display the cart contents.
     * This method calculates the total price including discounts from coupons.
     */
    public function index()
    {
        $coupons = session('coupons', []);
        $discount = 0;
        $subtotal = 0;
        $total = 0;
        $items = [];

        if (Auth::check()) {
            // For authenticated users, load cart from database
            // Eager load necessary relationships for calculating subtotal correctly
            $cart = Cart::with(['items.product', 'items.size'])->where('user_id', Auth::id())->first();
            $items = $cart?->items ?? [];

            foreach ($items as $item) {
                $itemPrice = 0;

                // Calculate base price (product price or size price)
                if ($item->product) {
                    // Use null coalescing to ensure a default of 0 if price is null
                    $basePrice = $item->size?->price ?? $item->product->price ?? 0;
                    $itemPrice += $basePrice;
                } else {
                    // If product doesn't exist, skip this item to prevent errors
                    continue;
                }

                // Calculate topping price
                $toppingPrice = 0;
                if (!empty($item->topping_id)) {
                    $toppingIds = explode(',', $item->topping_id);
                    // Sum prices from Product_topping model for selected toppings
                    $toppingPrice = Product_topping::whereIn('id', $toppingIds)->sum('price');
                    $itemPrice += $toppingPrice;
                }

                $subtotal += $itemPrice * $item->quantity;
            }
        } else {
            // For guest users, load cart from session
            $cartSession = session('cart', []);
            // Transform session cart items into a more usable structure for the view
            foreach ($cartSession as $key => $item) {
                $items[] = (object) $item; // Cast to object for consistent access in view
                // The 'price' in session cart items should already be the total for that item
                $subtotal += $item['price'] ?? 0; // Use null coalescing for safety
            }
            // Assign the session cart to $cart for consistency with authenticated flow
            $cart = (object)['items' => $items]; // Create a dummy cart object for the view
        }

        // Apply coupons to the subtotal
        foreach ($coupons as $coupon) {
            if ($coupon['type'] === 'percent') {
                $discount += ($subtotal * $coupon['discount']) / 100;
            } elseif ($coupon['type'] === 'fixed') {
                $discount += $coupon['discount'];
            }
        }

        // Ensure total doesn't go below zero
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

    /**
     * Add a product to the cart or update its quantity.
     * This method handles both authenticated users (database cart) and guest users (session cart).
     */
    public function addToCart(Request $request, $id)
    {
        // Find the product, or throw a 404 if not found
        $sanpham = Sanpham::findOrFail($id);

        // Get size details
        $sizeId = $request->input('size_id');
        $size = Size::find($sizeId);

        // Get topping IDs, ensure they are integers and sorted for consistent key generation
        $toppingIds = $request->input('topping_ids', []);
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);

        // Fetch topping details (prices) from Product_topping model
        $productToppings = Product_topping::whereIn('id', $toppingIds)->get();

        // Get quantity, ensuring it's at least 1
        $qty = max(1, (int)$request->input('qty', 1));

        // Calculate base price (from size or product)
        $basePrice = $size ? $size->price : ($sanpham->price ?? 0); // Use 0 if sanpham->price is null
        // Calculate total topping price
        $toppingPrice = $productToppings->sum('price');
        // Calculate unit price for a single item (base + toppings)
        $unitPrice = $basePrice + $toppingPrice;

        // Generate a unique key for the item based on product, size, and toppings
        $key = $sanpham->id . '-' . $sizeId . '-' . implode(',', $toppingIds);

        if (Auth::check()) {
            // User is authenticated, use database cart
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null, 'total' => 0] // Initialize total if new cart
            );

            // Check if an identical item already exists in the cart
            $existingItem = Cartdetail::where('cart_id', $cart->id)
                ->where('product_id', $sanpham->id)
                ->where('size_id', $sizeId)
                ->where('topping_id', implode(',', $toppingIds))
                ->first();

            if ($existingItem) {
                // If item exists, update its quantity
                $existingItem->quantity += $qty;
                $existingItem->save();
            } else {
                // If item does not exist, create a new cart detail entry
                $cartDetail = new Cartdetail([
                    'cart_id' => $cart->id,
                    'product_id' => $sanpham->id,
                    'size_id' => $sizeId,
                    'topping_id' => implode(',', $toppingIds),
                    'quantity' => $qty
                ]);
                $cartDetail->save();
            }

            // After modifying cart details, recalculate and update the main Cart's total
            $this->_updateCartTotal($cart);

        } else {
            // User is a guest, use session cart
            $cartSession = session('cart', []);

            if (isset($cartSession[$key])) {
                // If item exists in session cart, update its quantity and total price
                $cartSession[$key]['quantity'] += $qty;
                $cartSession[$key]['price'] = $unitPrice * $cartSession[$key]['quantity'];
            } else {
                // If item does not exist, add it to the session cart
                $cartSession[$key] = [
                    'sanpham_id'    => $sanpham->id,
                    'name'          => $sanpham->name,
                    'size_id'       => $sizeId,
                    'size_name'     => $size ? $size->size : null,
                    'size_price'    => $size ? $size->price : ($sanpham->price ?? 0),
                    'topping_ids'   => $toppingIds,
                    'quantity'      => $qty,
                    'unit_price'    => $unitPrice,
                    'price'         => $unitPrice * $qty, // Total price for this specific item
                    'image'         => $sanpham->image,
                    'topping_names' => [],
                    'topping_prices' => [] // Changed to 'topping_prices' for consistency
                ];

                // Populate topping names and prices for the session item
                foreach ($productToppings as $productTopping) {
                    $cartSession[$key]['topping_names'][] = $productTopping->topping;
                    $cartSession[$key]['topping_prices'][] = $productTopping->price;
                }
            }

            // Store the updated session cart
            session(['cart' => $cartSession]);
        }

        // Redirect to cart index with a success message
        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    /**
     * Tạo key duy nhất cho cart item dựa vào product, size, topping
     */
    private function _makeCartKey($productId, $sizeId, $toppingIds)
    {
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);
        return $productId . '-' . $sizeId . '-' . implode(',', $toppingIds);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng (AJAX)
     */
    public function updateCart(Request $request)
    {
        // Nhận key hoặc các tham số chi tiết
        $key = $request->input('key');
        $newQuantity = max(1, (int)$request->input('quantity', 1));
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $toppingIds = $request->input('topping_ids', []);
        if (!$key && $productId && $sizeId !== null) {
            $key = $this->_makeCartKey($productId, $sizeId, $toppingIds);
        }
        $toppingIdsString = implode(',', array_map('intval', (array)$toppingIds));

        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại.']);
                }
                return back()->with('error', 'Giỏ hàng không tồn tại.');
            }
            // Tìm cart detail theo key
            $cartDetail = null;
            if ($productId && $sizeId !== null) {
                $cartDetail = \App\Models\Cartdetail::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->where('size_id', $sizeId)
                    ->where('topping_id', $toppingIdsString)
                    ->first();
            } else if ($key) {
                // Nếu chỉ có key, cần parse key để lấy các thành phần
                $parts = explode('-', $key);
                $pid = $parts[0] ?? null;
                $sid = $parts[1] ?? null;
                $tids = isset($parts[2]) ? explode(',', $parts[2]) : [];
                $cartDetail = \App\Models\Cartdetail::where('cart_id', $cart->id)
                    ->where('product_id', $pid)
                    ->where('size_id', $sid)
                    ->where('topping_id', implode(',', $tids))
                    ->first();
            }
            if ($cartDetail) {
                $cartDetail->quantity = $newQuantity;
                $cartDetail->save();
                // Cập nhật lại tổng tiền
                $this->_updateCartTotal($cart);
                // Tính lại subtotal, total
                $subtotal = $cart->total;
                $total = $cart->total;
                // Tính lại thành tiền dòng
                $product = $cartDetail->product;
                $size = $cartDetail->size;
                $toppingPrice = 0;
                if (!empty($cartDetail->topping_id)) {
                    $toppingIdsArr = explode(',', $cartDetail->topping_id);
                    $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIdsArr)->sum('price');
                }
                $unitPrice = ($size ? $size->price : ($product->price ?? 0)) + $toppingPrice;
                $lineTotal = $unitPrice * $cartDetail->quantity;
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã cập nhật số lượng sản phẩm.',
                        'key' => $key,
                        'quantity' => $cartDetail->quantity,
                        'line_total' => $lineTotal,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);
                }
                return back()->with('success', 'Đã cập nhật số lượng sản phẩm.');
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
                return back()->with('error', 'Sản phẩm không tìm thấy trong giỏ hàng.');
            }
        } else {
            $cartSession = session('cart', []);
            if (isset($cartSession[$key])) {
                // Cập nhật lại giá cho item
                $sanpham = \App\Models\Sanpham::find($cartSession[$key]['sanpham_id']);
                $size = \App\Models\Size::find($cartSession[$key]['size_id']);
                $productToppings = \App\Models\Product_topping::whereIn('id', (array)$cartSession[$key]['topping_ids'])->get();
                $basePrice = $size ? $size->price : ($sanpham->price ?? 0);
                $toppingPrice = $productToppings->sum('price');
                $unitPrice = $basePrice + $toppingPrice;
                $cartSession[$key]['quantity'] = $newQuantity;
                $cartSession[$key]['price'] = $unitPrice * $newQuantity;
                session(['cart' => $cartSession]);
                // Tính lại subtotal và total cho session
                $subtotal = collect($cartSession)->sum(function($item) {
                    $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_price']);
                    return $unitPrice * $item['quantity'];
                });
                $coupons = session('coupons', []);
                $discount = 0;
                foreach ($coupons as $c) {
                    $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
                }
                $total = max(0, $subtotal - $discount);
                if ($request->ajax()) {
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
                return back()->with('success', 'Đã cập nhật số lượng sản phẩm.');
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
                return back()->with('error', 'Sản phẩm không tìm thấy trong giỏ hàng.');
            }
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng (AJAX)
     */
    public function removeItem(Request $request, $key = null)
    {
        // Nếu truyền key qua URL thì lấy, còn không thì lấy từ input
        $key = $key ?? $request->input('key');
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $toppingIds = $request->input('topping_ids', []);
        if (!$key && $productId && $sizeId !== null) {
            $key = $this->_makeCartKey($productId, $sizeId, $toppingIds);
        }
        $toppingIdsString = implode(',', array_map('intval', (array)$toppingIds));

        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
            if (!$cart) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại.']);
                }
                return back()->with('error', 'Giỏ hàng không tồn tại.');
            }
            // Tìm cart detail theo key
            $cartDetail = null;
            if ($productId && $sizeId !== null) {
                $cartDetail = \App\Models\Cartdetail::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->where('size_id', $sizeId)
                    ->where('topping_id', $toppingIdsString)
                    ->first();
            } else if ($key) {
                $parts = explode('-', $key);
                $pid = $parts[0] ?? null;
                $sid = $parts[1] ?? null;
                $tids = isset($parts[2]) ? explode(',', $parts[2]) : [];
                $cartDetail = \App\Models\Cartdetail::where('cart_id', $cart->id)
                    ->where('product_id', $pid)
                    ->where('size_id', $sid)
                    ->where('topping_id', implode(',', $tids))
                    ->first();
            }
            if ($cartDetail) {
                $cartDetail->delete();
                $this->_updateCartTotal($cart);
                $subtotal = $cart->total;
                $total = $cart->total;
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                        'key' => $key,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);
                }
                return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
                return back()->with('error', 'Sản phẩm không tìm thấy trong giỏ hàng.');
            }
        } else {
            $cartSession = session('cart', []);
            if (isset($cartSession[$key])) {
                unset($cartSession[$key]);
                session(['cart' => $cartSession]);
                $subtotal = collect($cartSession)->sum(function($item) {
                    $unitPrice = ($item['size_price'] ?? 0) + array_sum($item['topping_price']);
                    return $unitPrice * $item['quantity'];
                });
                $coupons = session('coupons', []);
                $discount = 0;
                foreach ($coupons as $c) {
                    $discount += ($c['type'] === 'percent') ? ($subtotal * $c['discount'] / 100) : $c['discount'];
                }
                $total = max(0, $subtotal - $discount);
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                        'key' => $key,
                        'subtotal' => $subtotal,
                        'total' => $total
                    ]);
                }
                return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không tìm thấy trong giỏ hàng.']);
                }
                return back()->with('error', 'Sản phẩm không tìm thấy trong giỏ hàng.');
            }
        }
    }

    /**
     * Hàm cập nhật tổng tiền cho cart (private)
     */
    private function _updateCartTotal($cart)
    {
        $subtotal = 0;
        $cart->load(['items.product', 'items.size']);
        foreach ($cart->items as $item) {
            $basePrice = $item->size?->price ?? $item->product->price ?? 0;
            $toppingPrice = 0;
            if (!empty($item->topping_id)) {
                $toppingIds = explode(',', $item->topping_id);
                $toppingPrice = \App\Models\Product_topping::whereIn('id', $toppingIds)->sum('price');
            }
            $itemPrice = $basePrice + $toppingPrice;
            $subtotal += $itemPrice * $item->quantity;
        }
        $cart->total = $subtotal;
        $cart->save();
    }
}
