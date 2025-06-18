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
        $orderStats = [
            'all' => 0,
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        if ($phone) {
            $request->validate([
                'phone' => 'required|numeric|digits_between:9,11',
            ]);

            $orders = Order::with(['orderDetails.product', 'orderDetails.size'])
                           ->where('phone', $phone)
                           ->orderBy('created_at', 'desc') 
                           ->get();

            // Tính toán thống kê đơn hàng
            $orderStats['all'] = $orders->count();
            $orderStats['pending'] = $orders->where('status', 'pending')->count();
            $orderStats['processing'] = $orders->where('status', 'processing')->count();
            $orderStats['completed'] = $orders->where('status', 'completed')->count();
            $orderStats['cancelled'] = $orders->where('status', 'cancelled')->count();
        }

        $toppings = Product_topping::all()->keyBy('id');

        return view('client.order_search', compact('orders', 'phone', 'toppings', 'orderStats'));
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
                $toppings = collect();
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

    public function getOrderDetail($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        $orderDetails = $order->orderDetails()->with(['product', 'size'])->get();
        
        // Format topping names
        $orderDetails->transform(function ($item) {
            $toppingNames = [];
            if (!empty($item->topping_id)) {
                $toppingIds = explode(',', trim($item->topping_id));
                foreach ($toppingIds as $id) {
                    $topping = Product_topping::find($id);
                    if ($topping) {
                        $toppingNames[] = $topping->topping;
                    }
                }
            }
            $item->topping_names = implode(', ', $toppingNames);
            $item->product_image = $item->product ? asset('storage/uploads/' . $item->product->image) : null;
            $item->size_name = $item->size ? $item->size->size : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
    }
}