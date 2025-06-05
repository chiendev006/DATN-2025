<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Product_topping;
use App\Models\Topping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MyaccountController extends Controller
{
public function index(Request $request)
{
    $userId = auth()->id();
    $orderStats = [
        'all' => Order::where('user_id', $userId)->count(),
        'pending' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
        'processing' => Order::where('user_id', $userId)->where('status', 'processing')->count(),
        'completed' => Order::where('user_id', $userId)->where('status', 'completed')->count(),
        'cancelled' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
    ];
    $query = OrderDetail::with(['order', 'product', 'size'])
        ->whereHas('order', function ($q) use ($userId, $request) {
            $q->where('user_id', $userId);

            if ($request->has('status') && $request->status !== 'all') {
                $q->where('status', $request->status);
            }
        });
    $orders = $query->get();
    $toppings = Product_topping::all()->keyBy('id');

    if ($request->ajax()) {
        return view('client.partials.order_list', compact('orders', 'toppings'))->render();
    }
    return view('client.myaccount', compact('orders', 'toppings', 'orderStats'));
}

public function cancelOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng. Đơn hàng không tồn tại hoặc không ở trạng thái chờ xác nhận.'
            ], 404);
        }
        $order->status = 'cancelled';
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công.'
        ]);
    }
    public function cancelMultiple(Request $request)
{
    $orderIds = $request->input('order_ids', []);

    if (empty($orderIds)) {
        return back()->with('error', 'Vui lòng chọn ít nhất một đơn hàng để hủy.');
    }

    foreach ($orderIds as $id) {
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'pending')
                      ->first();

        if ($order) {
            $order->status = 'cancelled';
            $order->save();
        }
    }

    return back()->with('success', 'Đã hủy các đơn hàng đã chọn thành công.');
}

}
