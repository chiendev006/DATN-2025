<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\Order;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email} {--order-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $orderId = $this->option('order-id');

        if ($orderId) {
            // Test với đơn hàng thực tế
            $order = Order::with('orderDetails.product', 'orderDetails.size')->find($orderId);
            
            if (!$order) {
                $this->error("Không tìm thấy đơn hàng với ID: {$orderId}");
                return 1;
            }

            $this->info("Gửi email xác nhận đơn hàng #{$order->id} đến {$email}...");
            
            try {
                Mail::to($email)->send(new OrderConfirmation($order));
                $this->info('✅ Email xác nhận đơn hàng đã được gửi thành công!');
            } catch (\Exception $e) {
                $this->error('❌ Lỗi gửi email: ' . $e->getMessage());
                return 1;
            }
        } else {
            // Test email đơn giản
            $this->info("Gửi email test đến {$email}...");
            
            try {
                Mail::raw('Đây là email test từ hệ thống.', function($message) use ($email) {
                    $message->to($email)
                            ->subject('Test Email - Hệ thống đơn hàng');
                });
                
                $this->info('✅ Email test đã được gửi thành công!');
            } catch (\Exception $e) {
                $this->error('❌ Lỗi gửi email: ' . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
} 