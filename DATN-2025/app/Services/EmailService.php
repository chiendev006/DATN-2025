<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdate;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Gửi email xác nhận đơn hàng
     */
    public function sendOrderConfirmation(Order $order)
    {
        try {
            if (!$order->email) {
                Log::info('Order confirmation email not sent - no email address', [
                    'order_id' => $order->id,
                    'customer_name' => $order->name
                ]);
                return false;
            }

            Mail::to($order->email)->send(new OrderConfirmation($order));
            
            Log::info('Order confirmation email sent successfully', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'customer_name' => $order->name
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Gửi email cập nhật trạng thái đơn hàng
     */
    public function sendOrderStatusUpdate(Order $order, $oldStatus, $newStatus)
    {
        try {
            if (!$order->email) {
                Log::info('Order status update email not sent - no email address', [
                    'order_id' => $order->id,
                    'customer_name' => $order->name,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
                return false;
            }

            // Chỉ gửi email khi trạng thái thực sự thay đổi
            if ($oldStatus === $newStatus) {
                Log::info('Order status update email not sent - status unchanged', [
                    'order_id' => $order->id,
                    'status' => $newStatus
                ]);
                return false;
            }

            Mail::to($order->email)->send(new OrderStatusUpdate($order, $oldStatus, $newStatus));
            
            Log::info('Order status update email sent successfully', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'customer_name' => $order->name,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Gửi email cho nhiều đơn hàng cùng lúc
     */
    public function sendBulkOrderConfirmations($orders)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($orders as $order) {
            if ($this->sendOrderConfirmation($order)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        Log::info('Bulk order confirmation emails completed', [
            'total_orders' => count($orders),
            'success_count' => $successCount,
            'fail_count' => $failCount
        ]);

        return [
            'total' => count($orders),
            'success' => $successCount,
            'failed' => $failCount
        ];
    }
} 