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
// MyAccountController.php

public function index(Request $request)
{
    $userId = auth()->id();

    // Tính thống kê đơn hàng pay_status
    $payStats = [
        'all' => Order::where('user_id', $userId)->count(),
        'pending' => Order::where('user_id', $userId)->where('pay_status', 0)->count(),
        'paid' => Order::where('user_id', $userId)->where('pay_status', 1)->count(),
        'cancelled' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
    ];

    // Query lấy order details kèm theo order, product, size theo user và filter pay_status nếu có
    $query = OrderDetail::with(['order', 'product', 'size'])
        ->whereHas('order', function ($q) use ($userId, $request) {
            $q->where('user_id', $userId);

            if ($request->has('pay_status') && $request->pay_status !== 'all') {
                $q->where('pay_status', $request->pay_status);
            }
        });

    $orders = $query->get();
    $toppings = Product_topping::all()->keyBy('id');

    if ($request->ajax()) {
        return view('client.partials.order_list', compact('orders', 'toppings'))->render();
    }

    return view('client.myaccount', compact('orders', 'toppings', 'payStats'));
}

public function cancelOrder($id)
{
    $order = Order::where('id', $id)
        ->where('user_id', auth()->id()) 
        ->where('status', 'pending')     
        ->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Không thể hủy đơn hàng.');
    }

    $order->status = 'cancelled';
    $order->save();

    return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công.');
}
}
