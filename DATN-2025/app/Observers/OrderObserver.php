<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\PointService;

class OrderObserver
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        \Log::info('POINT_DEBUG: OrderObserver updated called', [
            'order_id' => $order->id,
            'status' => $order->status,
            'pay_status' => $order->pay_status,
            'wasChanged_status' => $order->wasChanged('status'),
            'wasChanged_pay_status' => $order->wasChanged('pay_status'),
        ]);
        // Kiểm tra nếu trạng thái đơn hàng thay đổi thành "completed" và đã thanh toán (pay_status = '1')
        if ($order->wasChanged('status') && 
            $order->status === 'completed' && 
            $order->pay_status === '1') {
            try {
                $pointService = app(\App\Services\PointService::class);
                $pointService->earnPointsFromOrder($order);
            } catch (\Exception $e) {
                \Log::error('Lỗi tích điểm cho đơn hàng #' . $order->id . ': ' . $e->getMessage());
            }
        }

        // Hoàn điểm khi hủy đơn hàng
        if ($order->wasChanged('status') && 
            $order->status === 'cancelled' && 
            $order->wasChanged('cancel_reason')) {
            try {
                $pointService = app(\App\Services\PointService::class);
                $pointService->refundPointsFromOrder($order);
            } catch (\Exception $e) {
                \Log::error('Lỗi hoàn điểm cho đơn hàng #' . $order->id . ': ' . $e->getMessage());
            }
        }
    }
} 