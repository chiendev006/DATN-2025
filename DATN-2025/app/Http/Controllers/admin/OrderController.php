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
        )->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.order.index', ['orders' => $orders]);
    }

    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->pay_status = (string) $request->input('pay_status');
        $oldStatus = $order->status;
        $status = $request->input('status');
        $ship_status = $request->input('ship_status_hidden', $request->input('ship_status'));

        // Nếu là đơn nhân viên hoặc ship_status là 'na', set ship_status về 'not_applicable'
        if (($order->phone == 'N/A') || $ship_status == 'na' || empty($ship_status)) {
            $ship_status = 'not_applicable';
        }

        // Không còn tự động đồng bộ status/pay_status/ship_status nữa
        $order->status = $status;
        $order->ship_status = $ship_status;

        if ($status === 'cancelled') {
            if ($order->cancel_reason && $oldStatus === 'cancelled') {
            } else {
                $cancelReason = $request->input('cancel_reason');
                if (!str_contains($cancelReason, '(Admin hủy)')) {
                    $cancelReason = '(Admin hủy) ' . $cancelReason;
                }
                $order->cancel_reason = $cancelReason;
            }
        } else {
            $order->cancel_reason = $request->input('cancel_reason');
        }

        $order->save();
        $msg = 'Cập nhật đơn hàng thành công!';
        return redirect()->route('admin.order.index')->with('success', $msg);
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
                        $topping_arr[] ="<p>". $tp->topping . ' - ' . number_format($tp->price) . ' VND</p>';
                    }
                }
            }
            return [
                'product_name' => $product_name,
                'product_image' => $product_image,
                'size' => $size_name,
                'topping' => implode('', $topping_arr),
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
        $perPage = $request->input('per_page', 10);
        $query = Order::query()->select('orders.*');

        if ($request->filled('pay_status')) {
            $query->where('pay_status', $request->input('pay_status'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('ship_status')) {
            $query->where('ship_status', $request->input('ship_status'));
        }

        $orders = $query->paginate($perPage);
        return view('admin.order.index', compact('orders'));
    }

    /**
     *
     *
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
