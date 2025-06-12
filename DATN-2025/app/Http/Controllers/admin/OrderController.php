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
    public function ordersIndex(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $orders = Order::select(
            'orders.*'
        )->paginate($perPage);

        return view('admin.order.index', ['orders' => $orders]);
    }

    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->pay_status = $request->input('pay_status');
        $order->status = $request->input('status');
        $order->cancel_reason = $request->input('cancel_reason');
        $order->save();
        return redirect()->route('admin.order.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }

   public function delete($id)
    {
        \App\Models\Orderdetail::where('order_id', $id)->get()->each->delete();
        \App\Models\Order::findOrFail($id)->delete();
        return redirect()->route('admin.order.index')->with('success', 'Đã xóa mềm đơn hàng thành công!');
    }


    public function showJson($id)
    {
        $order = \App\Models\Order::with('details')->findOrFail($id);
        $details = $order->details->map(function($detail) {
            $product = \App\Models\sanpham::find($detail->product_id);
            $product_name = $product ? $product->name : '';
            $product_image = $product ? $product->image : '';
            $size = $detail->size_id ? \App\Models\Size::find($detail->size_id) : null;
            $size_name = $size ? ($size->size . ' - ' . number_format($size->price) . ' VND') : '';
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
        $orderArr['product_total'] = $order->total - $order->shipping_fee - $order->coupon_total_discount;

        return response()->json($orderArr);
    }

    /**
     * Lọc đơn hàng theo trạng thái thanh toán hoặc trạng thái đơn hàng.
     * Truyền query string: ?pay_status=0|1|2 hoặc ?status=pending|processing|completed|cancelled
     */
    public function filterOrders(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = Order::query()->select('orders.*');
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
        $orders = $query->paginate($perPage);
        return view('admin.order.index', ['orders' => $orders]);
    }

    /**
     * Tìm kiếm đơn hàng theo transaction_id (mã đơn hàng).
     * Truyền query string: ?transaction_id=xxxx
     */
    public function searchByTransactionId(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $transactionId = $request->input('transaction_id');
        $orders = Order::select('orders.*')
            ->where('name', 'like', "%$transactionId%")
            ->orWhere('phone', 'like', "%$transactionId%")
            ->paginate($perPage);
        return view('admin.order.index', ['orders' => $orders]);
    }
}
