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

        $autoAdjustMsg = null;
        $originalStatus = $status;
        $originalShipStatus = $ship_status;

        // Đồng bộ logic status và ship_status
        if ($status === 'pending') {
            if ($ship_status === 'out_for_delivery') {
                $status = 'processing';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái đơn sang "Đã xác nhận" vì trạng thái giao hàng là "Đang giao".';
            } elseif ($ship_status === 'delivered') {
                $status = 'completed';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái đơn sang "Hoàn thành" vì trạng thái giao hàng là "Đã giao".';
            } elseif (in_array($ship_status, ['failed_delivery', 'returned_to_store'])) {
                $status = 'cancelled';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái đơn sang "Đã hủy" vì trạng thái giao hàng là "Giao thất bại" hoặc "Đã trả hàng".';
            }
        }
        if ($status === 'processing') {
            if ($ship_status === 'delivered') {
                $status = 'completed';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái đơn sang "Hoàn thành" vì trạng thái giao hàng là "Đã giao".';
            } elseif (in_array($ship_status, ['failed_delivery', 'returned_to_store'])) {
                $status = 'cancelled';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái đơn sang "Đã hủy" vì trạng thái giao hàng là "Giao thất bại" hoặc "Đã trả hàng".';
            }
        }
        if ($status === 'completed') {
            if ($ship_status !== 'delivered') {
                $ship_status = 'delivered';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái giao hàng sang "Đã giao" vì trạng thái đơn là "Hoàn thành".';
            }
        }
        if ($status === 'cancelled') {
            if (!in_array($ship_status, ['failed_delivery', 'returned_to_store'])) {
                $ship_status = 'failed_delivery';
                $autoAdjustMsg = 'Hệ thống tự động chuyển trạng thái giao hàng sang "Giao thất bại" vì trạng thái đơn là "Đã hủy".';
            }
            // Nếu đã thanh toán thì chuyển sang hoàn tiền
            if ($order->pay_status == '1') {
                $order->pay_status = '3'; // Hoàn tiền
                $autoAdjustMsg .= ' Đơn đã thanh toán, chuyển trạng thái sang "Hoàn tiền".';
            } else if ($order->pay_status == '0') { // Nếu chưa thanh toán
                $order->pay_status = '2'; // Đã hủy
                $autoAdjustMsg .= ' Đơn chưa thanh toán, chuyển trạng thái sang "Đã hủy".';
            }
        }

        // Tự động cập nhật trạng thái thanh toán nếu đơn đã hoàn thành và đã giao
        if ($status === 'completed' && $ship_status === 'delivered' && $order->pay_status != '1') {
            $order->pay_status = '1';
            $autoAdjustMsg .= ' Hệ thống tự động chuyển trạng thái thanh toán sang "Đã thanh toán" vì đơn đã giao và hoàn thành.';
        }

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
        if ($autoAdjustMsg) {
            $msg .= ' ' . $autoAdjustMsg;
        }
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
