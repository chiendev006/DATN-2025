<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cartdetail;
use App\Models\Order;
use App\Models\Product_topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderSearchController extends Controller
{
    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $phone = $request->input('phone');
        
        $orders = collect();

        if ($phone) {
            $request->validate([
                'phone' => 'required|numeric|digits_between:9,11',
            ]);

            $orders = Order::with(['orderDetails.product', 'orderDetails.size'])
                           ->where('phone', $phone)
                           ->orderBy('created_at', 'desc') 
                           ->get();
        }

        $toppings = Product_topping::all()->keyBy('id');

        return view('client.order_search', compact('orders', 'phone', 'toppings'));
    }
     public function cancelOrder($id, Request $request)
    {
        $order = Order::where('id', $id)
                      ->where('status', 'pending')
                      ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy. Đơn hàng không tồn tại hoặc đã được xử lý.'
            ], 404);
        }

        $reason = $request->input('cancel_reason', 'Khách hàng hủy đơn');

        $order->status = 'cancelled';
        $order->cancel_reason = $reason;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công.'
        ]);
    }

    /**
     * 
     */
    public function checkOrderStatus($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'status' => 'not_found']);
        }

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    /**
     * Đặt lại một đơn hàng cũ.
     */
    public function reorder($id)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.size'])->find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng!');
        }

        session()->forget('cart');

        $cartSession = [];

        foreach ($order->orderDetails as $item) {
            if ($item->product) {
                // Xử lý topping IDs
                $toppingIds = [];
                if (!empty($item->topping_id)) {
                    $toppingIds = array_filter(array_map('intval', explode(',', $item->topping_id)));
                }

                // Lấy thông tin topping từ product_topping
                $toppings = [];
                if (!empty($toppingIds)) {
                    $toppings = DB::table('product_topping')
                        ->whereIn('id', $toppingIds)
                        ->select('topping', 'price')
                        ->get();
                }

                // Tạo key cho giỏ hàng
                $key = $item->product_id . '-' . ($item->size_id ?? '0') . '-' . implode(',', $toppingIds);

                $cartSession[$key] = [
                    'product_id' => $item->product_id,
                    'sanpham_id' => $item->product_id,
                    'name' => $item->product->name,
                    'image' => $item->product->image,
                    'price' => $item->product_price,
                    'size_id' => $item->size_id,
                    'size_name' => optional($item->size)->size ?? 'Không rõ',
                    'size_price' => optional($item->size)->price ?? 0,
                    'topping_names' => $toppings->pluck('topping')->toArray(),
                    'topping_prices' => $toppings->pluck('price')->toArray(),
                    'topping_ids' => implode(',', $toppingIds),
                    'quantity' => $item->quantity,
                ];
            }
        }

        session()->put('cart', $cartSession);

        return redirect()->route('cart.index')->with('success', 'Đã thêm các sản phẩm từ đơn hàng cũ vào giỏ hàng!');
    }

    private function getToppingNames($toppingIds)
    {
        if (!$toppingIds) return 'Không có';

        $ids = is_array($toppingIds) ? $toppingIds : explode(',', $toppingIds);

        $toppingNames = DB::table('product_topping')
            ->join('topping', 'product_topping.topping', '=', 'topping.id')
            ->whereIn('product_topping.id', $ids)
            ->pluck('topping.name')
            ->toArray();

        return implode(', ', $toppingNames);
    }

    private function normalizeToppingIds($raw)
    {
        if (is_array($raw)) {
            return array_filter(array_map('intval', $raw));
        }

        if (is_string($raw) && trim($raw) !== '') {
            return array_filter(array_map('intval', explode(',', $raw)));
        }

        return [];
    }
}