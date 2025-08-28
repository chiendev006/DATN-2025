<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;


class OrderController extends Controller
{
    protected $pointService;
    protected $emailService;

    public function __construct(PointService $pointService, EmailService $emailService)
    {
        $this->pointService = $pointService;
        $this->emailService = $emailService;
    }

    /**
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
        $order = \App\Models\Order::with(['user', 'customer'])->findOrFail($id);

        if ($request->has('pay_status') && $request->input('pay_status') !== '') {
            $order->pay_status = (string) $request->input('pay_status');
        }

        $oldStatus = $order->status;
        $status = $request->input('status');

        $order->status = $status;

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

        // Gửi email cập nhật trạng thái đơn hàng
        try {
            $this->emailService->sendOrderStatusUpdate($order, $oldStatus, $status);
            \Log::info('Order status update email sent', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $status
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'error' => $e->getMessage()
            ]);
            // Không throw exception để không ảnh hưởng đến quá trình cập nhật
        }

        // Tích điểm khi đơn hàng hoàn thành
        if ($status === 'completed' && $order->pay_status === '1') {
            \Log::info('POINT_DEBUG: Attempting to earn points', [
                'order_id' => $order->id,
                'status' => $status,
                'pay_status' => $order->pay_status,
                'user_id' => $order->user_id,
                'customer_id' => $order->customer_id,
                'has_user' => $order->user ? 'yes' : 'no',
                'has_customer' => $order->customer ? 'yes' : 'no',
                'user_role' => $order->user ? $order->user->role : 'no_user',
                'customer_role' => $order->customer ? $order->customer->role : 'no_customer'
            ]);

            try {
                $earnedPoints = $this->pointService->earnPointsFromOrder($order);
                if ($earnedPoints > 0) {
                    $msg = "Cập nhật đơn hàng thành công! Đã tích {$earnedPoints} điểm cho khách hàng.";
                } else {
                    $msg = 'Cập nhật đơn hàng thành công!';
                }
            } catch (\Exception $e) {
                \Log::error('POINT_DEBUG: Error earning points', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $msg = 'Cập nhật đơn hàng thành công! (Lỗi tích điểm: ' . $e->getMessage() . ')';
            }
        }
        // Hoàn điểm khi hủy đơn hàng
        elseif ($status === 'cancelled' && $oldStatus !== 'cancelled') {
            // Xác định đơn tại quầy
            $isStaffOrder = false;
            if ($order->customer_id) {
                // Đơn tại quầy có customer_id
                $isStaffOrder = true;
            } elseif ($order->user_id && $order->user && $order->user->role === 1) {
                // Đơn online có user_id là nhân viên
                $isStaffOrder = true;
            } elseif ($order->phone === 'N/A' || $order->phone === 'Không có' || $order->phone === 'Nhân viên thu ngân') {
                // Đơn tại quầy không có customer_id nhưng có phone đặc biệt
                $isStaffOrder = true;
            } elseif (str_contains($order->name, 'Khách lẻ') || str_contains($order->name, 'Khách Vãng Lai')) {
                // Đơn tại quầy có tên đặc biệt
                $isStaffOrder = true;
            }

            // Logic hoàn điểm khác nhau cho đơn tại quầy và đơn online
            $shouldRefund = false;
            if ($isStaffOrder) {
                // Đơn tại quầy: Chỉ cần trạng thái đơn là đã hủy
                $shouldRefund = true;
            } else {
                // Đơn online: Cần cả trạng thái đơn và trạng thái thanh toán đều là đã hủy
                $shouldRefund = ($order->pay_status === '2');
            }

            if ($shouldRefund) {
                \Log::info('POINT_DEBUG: Attempting to refund points', [
                    'order_id' => $order->id,
                    'status' => $status,
                    'old_status' => $oldStatus,
                    'pay_status' => $order->pay_status,
                    'is_staff_order' => $isStaffOrder,
                    'user_id' => $order->user_id,
                    'customer_id' => $order->customer_id,
                    'order_name' => $order->name,
                    'order_phone' => $order->phone,
                    'has_user' => $order->user ? 'yes' : 'no',
                    'has_customer' => $order->customer ? 'yes' : 'no',
                    'user_role' => $order->user ? $order->user->role : 'no_user',
                    'customer_role' => $order->customer ? $order->customer->role : 'no_customer'
                ]);

                try {
                    $refundedPoints = $this->pointService->refundPointsFromOrder($order);
                    $refundedEarnedPoints = $this->pointService->refundEarnedPointsFromOrder($order);

                    $totalRefunded = $refundedPoints + $refundedEarnedPoints;

                    if ($totalRefunded > 0) {
                        $msg = "Cập nhật đơn hàng thành công! Đã hoàn {$totalRefunded} điểm cho khách hàng.";
                        if ($refundedPoints > 0 && $refundedEarnedPoints > 0) {
                            $msg = "Cập nhật đơn hàng thành công! Đã hoàn {$refundedPoints} điểm sử dụng và {$refundedEarnedPoints} điểm tích lũy cho khách hàng.";
                        } elseif ($refundedPoints > 0) {
                            $msg = "Cập nhật đơn hàng thành công! Đã hoàn {$refundedPoints} điểm sử dụng cho khách hàng.";
                        } elseif ($refundedEarnedPoints > 0) {
                            $msg = "Cập nhật đơn hàng thành công! Đã hoàn {$refundedEarnedPoints} điểm tích lũy cho khách hàng.";
                        }
                    } else {
                        $msg = 'Cập nhật đơn hàng thành công!';
                    }
                } catch (\Exception $e) {
                    \Log::error('POINT_DEBUG: Error refunding points', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $msg = 'Cập nhật đơn hàng thành công! (Lỗi hoàn điểm: ' . $e->getMessage() . ')';
                }
            } else {
                \Log::info('POINT_DEBUG: Not refunding points - conditions not met', [
                    'order_id' => $order->id,
                    'status' => $status,
                    'pay_status' => $order->pay_status,
                    'is_staff_order' => $isStaffOrder,
                    'order_name' => $order->name,
                    'order_phone' => $order->phone,
                    'user_id' => $order->user_id,
                    'customer_id' => $order->customer_id,
                    'user_role' => $order->user ? $order->user->role : 'no_user',
                    'customer_role' => $order->customer ? $order->customer->role : 'no_customer',
                    'should_refund' => $shouldRefund,
                    'refund_condition' => $isStaffOrder ? 'staff_order_should_refund' : 'online_order_needs_pay_status_2'
                ]);
                $msg = 'Cập nhật đơn hàng thành công!';
            }
        } else {
            \Log::info('POINT_DEBUG: No point operations', [
                'order_id' => $order->id,
                'status' => $status,
                'pay_status' => $order->pay_status,
                'old_status' => $oldStatus,
                'earn_condition_met' => ($status === 'completed' && $order->pay_status === '1') ? 'yes' : 'no',
                'refund_condition_met' => ($status === 'cancelled' && $oldStatus !== 'cancelled') ? 'yes' : 'no'
            ]);
            $msg = 'Cập nhật đơn hàng thành công!';
        }
        $content3='';
        $title='<strong>Admin cập nhật trạng thái đơn hàng:</strong> <br>';
        if($status=='completed'){
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>Đã hoàn thành</b> <br>";
        } else if($status=='shipping') {
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>Đang ship</b> <br>";
        } else if( $status=='pending'){
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>Chờ xác nhận</b> <br>";
        } else if($status=='processing') {
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>Đã xác nhận</b> <br>";
        } else{
            $content2=" *<span style='color: red;'>Trạng thái:</span> <b>Đã hủy</b> <br>";
        }
        $content1=" *<span style='color: red;'>Mã đơn hàng:</span> <b>$order->id</b> <br>";

        if($status=='cancelled'){
            $content3=" *<span style='color: red;'>Lý do hủy:</span> <b>$order->cancel_reason</b> <br>";
        }

        \App\Models\historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' => $title . $content1 . $content2 . $content3,
        ]);
        return redirect()->route('admin.order.index')->with('success', $msg);
    }

   public function delete($id)
    {
        \App\Models\Orderdetail::where('order_id', $id)->get()->each->delete();
        \App\Models\Order::findOrFail($id)->delete();
        return redirect()->route('admin.order.index')->with('success', 'Đã xóa mềm đơn hàng thành công!');
    }

    /**
     * Hiển thị chi tiết đơn hàng dưới dạng JSON
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function showJson($id)
    {
        $order = \App\Models\Order::with('details')->findOrFail($id);
        $details = $order->details->map(function($detail) {
            $product = \App\Models\sanpham::withTrashed()->find($detail->product_id);
            $product_name = $product ? $product->name : 'Sản phẩm đã bị xóa';
            
            // Xử lý ảnh sản phẩm
            $product_image = '';
            if ($product && $product->image) {
                $product_image = $product->image; // Chỉ trả về tên file, không phải đường dẫn đầy đủ
            }
            

            
            // Xử lý size
            $size = $detail->size_id ? \App\Models\Size::withTrashed()->find($detail->size_id) : null;
            $size_name = $size ? ($size->size . ' - ' . number_format($size->price) . ' VND') : '';
            
            // Xử lý topping
            $topping_arr = [];
            if (!empty($detail->topping_id)) {
                $topping_ids = array_filter(array_map('trim', explode(',', $detail->topping_id)));
                if (!empty($topping_ids)) {
                    $toppings = \App\Models\Product_topping::withTrashed()->whereIn('id', $topping_ids)->get();
                    foreach ($toppings as $tp) {
                        $topping_arr[] = $tp->topping . ' - ' . number_format($tp->price) . ' VND';
                    }
                }
            }
            

            
            return [
                'product_name' => $product_name,
                'product_image' => $product_image,
                'size' => $size_name ?: 'Không chọn',
                'topping' => !empty($topping_arr) ? implode('<br>', $topping_arr) : 'Không chọn',
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
     *
     * Truyền query string: ?pay_status=0|1|2 hoặc ?status=pending|processing|shipping|completed|cancelled
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
        if ($request->filled('order_type')) {
            if ($request->order_type == 'staff') {
                $query->where(function($q) {
                    $q->whereHas('user', function($u) {
                        $u->whereIn('role', [1, 21, 22]);
                    })
                    ->orWhere(function($q2) {
                        $q2->whereNull('user_id')
                            ->where(function($q3) {
                                $q3->where('phone', 'N/A')
                                    ->orWhere('phone', 'Nhân viên thu ngân')
                                    ->orWhere('phone', 'Không có')
                                    ->orWhere('name', 'like', '%Khách lẻ%')
                                    ->orWhere('name', 'like', '%Khách Vãng Lai%');
                            });
                    });
                });
            } elseif ($request->order_type == 'web') {
                $query->where(function($q) {
                    $q->whereHas('user', function($u) {
                        $u->where('role', 0);
                    })
                    ->orWhere(function($q2) {
                        $q2->whereNull('user_id')
                            ->where('phone', '!=', 'N/A')
                            ->where('phone', '!=', 'Nhân viên thu ngân')
                            ->where('phone', '!=', 'Không có')
                            ->where('name', 'not like', '%Khách lẻ%')
                            ->where('name', 'not like', '%Khách Vãng Lai%');
                    });
                });
            }
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
    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OrdersExport($request->all()), 'HÓA ĐƠN.xlsx');
    }
}
