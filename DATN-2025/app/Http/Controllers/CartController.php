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
        $items = collect([]); // Initialize as empty collection

        if (Auth::check()) {
            // For authenticated users, load cart from database
            $cart = Cart::where('user_id', Auth::id())->first();
            
            // If cart doesn't exist, create it
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'total' => 0
                ]);
            }
            
            // Eager load relationships and get cart items
            $cart->load(['cartdetails.product', 'cartdetails.size']);
            $items = $cart->cartdetails;

            if ($items) {
                // Calculate subtotal
                foreach ($items as $item) {
                    // Skip if product doesn't exist
                    if (!$item->product) continue;

                    // Get base price from size or product
                    $size = $item->size;
                    $basePrice = $size ? $size->price : $item->product->price;
                    
                    // Add topping prices if any
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
            // For guest users, load cart from session
            $cartSession = session('cart', []);
            foreach ($cartSession as $key => $item) {
                $items->push((object) $item);
                // Calculate subtotal from session cart
                $basePrice = $item['size_price'] ?? 0;
                $toppingPrice = array_sum($item['topping_prices'] ?? []);
                $subtotal += ($basePrice + $toppingPrice) * ($item['quantity'] ?? 1);
            }
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
    private function _makeCartKey($productId, $sizeId, $toppingIds = [])
    {
        $toppingIds = array_map('intval', (array)$toppingIds);
        sort($toppingIds);
        return implode('-', [$productId, $sizeId, implode(',', $toppingIds)]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng (AJAX)
     */
    public function updateCart(Request $request)
    {
        \Log::info('Update Cart Request:', $request->all());
        
        try {
            // Nhận key hoặc các tham số chi tiết
            $key = $request->input('key');
            $newQuantity = max(1, (int)$request->input('quantity', 1));
            $productId = $request->input('product_id');
            $sizeId = $request->input('size_id');
            $toppingIds = $request->input('topping_ids', []);
            
            // Chuẩn hóa topping IDs
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

                // Tìm cart detail theo key
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

                // Tính lại thành tiền dòng
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

                // Cập nhật lại giá cho item
                $sanpham = Sanpham::find($cartSession[$key]['sanpham_id']);
                $size = Size::find($cartSession[$key]['size_id']);
                $productToppings = Product_topping::whereIn('id', (array)$cartSession[$key]['topping_ids'])->get();
                
                $basePrice = $size ? $size->price : ($sanpham->price ?? 0);
                $toppingPrice = $productToppings->sum('price');
                $unitPrice = $basePrice + $toppingPrice;
                
                $cartSession[$key]['quantity'] = $newQuantity;
                $cartSession[$key]['price'] = $unitPrice * $newQuantity;
                
                session(['cart' => $cartSession]);
                
                // Tính lại subtotal và total
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
     * Xóa sản phẩm khỏi giỏ hàng (AJAX)
     */
    public function removeItem(Request $request, $key = null)
    {
        try {
            \Log::info('Remove Cart Item Request:', [
                'key' => $key,
                'request_data' => $request->all()
            ]);

            // Nếu truyền key qua URL thì lấy, còn không thì lấy từ input
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

                // Tìm cart detail theo key
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

                    // Tính lại subtotal và total
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
     * Hàm cập nhật tổng tiền cho cart (private)
     */
    private function _updateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart->cartdetails as $detail) {
            // Skip if product doesn't exist
            if (!$detail->product) continue;

            // Get base price from size or product
            $size = $detail->size;
            $basePrice = $size ? $size->price : $detail->product->price;
            
            // Add topping prices if any
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
