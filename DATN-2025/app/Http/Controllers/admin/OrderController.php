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

    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->pay_status = $request->input('pay_status');
        $order->save();
        return redirect()->route('admin.order.index')->with('success', 'Cập nhật trạng thái thanh toán thành công!');
    }

    public function delete($id)
    {
        // Xóa order_detail trước
        \App\Models\Orderdetail::where('order_id', $id)->delete();
        // Xóa order
        \App\Models\Order::where('id', $id)->delete();
        return redirect()->route('admin.order.index')->with('success', 'Đã xóa đơn hàng thành công!');
    }

    public function showJson($id)
    {
        $order = \App\Models\Order::with('details')->findOrFail($id);
        $details = $order->details->map(function($detail) {
            // Lấy sản phẩm
            $product = \App\Models\sanpham::find($detail->product_id);
            $product_name = $product ? $product->name : '';
            $product_image = $product ? $product->image : '';
            // Lấy size
            $size = $detail->size_id ? \App\Models\Size::find($detail->size_id) : null;
            $size_name = $size ? ($size->size . ' - ' . number_format($size->price) . ' VND') : '';
            // Lấy topping (có thể nhiều)
            $topping_arr = [];
            if (!empty($detail->topping_id)) {
                $topping_ids = array_filter(array_map('trim', explode(',', $detail->topping_id)));
                if (!empty($topping_ids)) {
                    $toppings = \App\Models\Product_topping::whereIn('id', $topping_ids)->get();
                    foreach ($toppings as $tp) {
                        $topping_arr[] = $tp->topping . ' - ' . number_format($tp->price) . ' VND';
                    }
                }
            }
            return [
                'product_name' => $product_name,
                'product_image' => $product_image,
                'size' => $size_name,
                'topping' => implode(', ', $topping_arr),
                'quantity' => $detail->quantity,
                'total' => $detail->total,
                'note' => $detail->note,
            ];
        });
        $orderArr = $order->toArray();
        $orderArr['details'] = $details;
        return response()->json($orderArr);
    }

    /**
     * Lọc đơn hàng theo trạng thái thanh toán hoặc trạng thái đơn hàng.
     * Truyền query string: ?pay_status=0|1|2 hoặc ?status=pending|processing|completed|cancelled
     */
    public function filterOrders(Request $request)
    {
        $query = Order::query();
        $hasPayStatus = $request->filled('pay_status');
        $hasStatus = $request->filled('status');

        if ($hasPayStatus && $hasStatus) {
            $query->where('pay_status', $request->input('pay_status'))
                  ->where('status', $request->input('status'));
        } elseif ($hasPayStatus) {
            $query->where('pay_status', $request->input('pay_status'));
        } elseif ($hasStatus) {
            $query->where('status', $request->input('status'));
        }
        // Nếu cả hai đều không có thì không where gì, trả về toàn bộ
        $orders = $query->paginate(10);
        return view('admin.order.index', ['orders' => $orders]);
    }

    /**
     * Tìm kiếm đơn hàng theo transaction_id (mã đơn hàng).
     * Truyền query string: ?transaction_id=xxxx
     */
    public function searchByTransactionId(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $orders = Order::where('transaction_id', 'like', "%$transactionId%")
            ->paginate(10);
        return view('admin.order.index', ['orders' => $orders]);
    }
}
