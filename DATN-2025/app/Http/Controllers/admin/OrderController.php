<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng.
     */
    public function index()
    {
        $orders = Order::paginate(10);
        return view('admin.order.index', ['orders' => $orders]);
    }

    public function show($id)
    {
        // TODO: Hiển thị chi tiết đơn hàng
    }

    public function delete($id)
    {
        // Xóa order_detail trước
        \App\Models\Orderdetail::where('order_id', $id)->delete();
        // Xóa order
        \App\Models\Order::where('id', $id)->delete();
        return redirect()->route('admin.order.index')->with('success', 'Đã xóa đơn hàng thành công!');
    }
}
