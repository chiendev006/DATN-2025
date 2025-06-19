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
    $user = auth()->user();
    $userId = $user->id;

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

    $orders = $query->get()->groupBy('order_id');
    $oder = Order::all();
    $toppings = Product_topping::all()->keyBy('id');

    if ($request->ajax()) {
        return view('client.partials.order_list', compact('orders', 'toppings'))->render();
    }

    return view('client.myaccount', compact('orders', 'toppings', 'orderStats', 'user', 'oder')); // <--- Truyền $user vào view
}


public function cancelOrder($id, Request $request)
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

    $reason = $request->input('cancel_reason', 'Người dùng không cung cấp lý do');

    $order->status = 'cancelled';
    $order->ship_status = 'failed_delivery';
    // Nếu đã thanh toán thì chuyển sang hoàn tiền
    if ($order->pay_status === '1') {
        $order->pay_status = '3'; // Hoàn tiền
    } else {
        $order->pay_status = '2'; // Đã hủy (chưa thanh toán)
    }
    $order->cancel_reason = '(Khách hàng hủy) ' . $reason;
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
public function ajaxUpdate(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->address = $request->address;

    if ($request->hasFile('image')) {
        if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
            unlink(storage_path('app/public/' . $user->image));
        }

        $path = $request->file('image')->store('avatars', 'public');
        $user->image = $path;
    }

    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Cập nhật thông tin thành công!',
        'data' => [
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
            'image_url' => $user->image ? asset('storage/' . $user->image) : null,
        ]
    ]);
}

public function checkOrderStatus($id)
{
    
    $order = Order::where('id', $id)
                  ->where('user_id', auth()->id())
                  ->first();

    if (!$order) {
        return response()->json(['success' => false, 'status' => 'not_found']);
    }

    return response()->json(['success' => true, 'status' => $order->status]);
}

}
