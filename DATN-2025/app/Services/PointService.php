<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\PointSetting;
use App\Models\PointTransaction;

class PointService
{
    /**
     * Tính số điểm sẽ được tích từ đơn hàng
     */
    public function calculateEarnedPoints($orderTotal)
    {
        if (!PointSetting::isPointsSystemEnabled()) {
            return 0;
        }

        $pointsPerVnd = PointSetting::getPointsPerVnd();
        return (int) ($orderTotal / $pointsPerVnd);
    }

    /**
     * Tính số tiền giảm giá từ điểm sử dụng
     */
    public function calculateDiscountFromPoints($points)
    {
        $vndPerPoint = PointSetting::getVndPerPoint();
        return $points * $vndPerPoint;
    }

    /**
     * Tính số điểm cần để giảm giá một số tiền
     */
    public function calculatePointsNeeded($discountAmount)
    {
        $vndPerPoint = PointSetting::getVndPerPoint();
        return (int) ($discountAmount / $vndPerPoint);
    }

    /**
     * Tích điểm cho user khi đơn hàng hoàn thành
     */
    public function earnPointsFromOrder(Order $order)
    {
        \Log::info('POINT_DEBUG: earnPointsFromOrder called', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'customer_id' => $order->customer_id,
            'user_role' => $order->user ? $order->user->role : null,
            'status' => $order->status,
            'pay_status' => $order->pay_status,
        ]);

        // Xác định khách hàng để tích điểm
        $customer = null;
        if ($order->customer_id) {
            // Đơn tại quầy có customer_id
            $customer = \App\Models\User::find($order->customer_id);
        } elseif ($order->user_id && $order->user && $order->user->role === 0) {
            // Đơn online có user_id là khách hàng
            $customer = $order->user;
        }

        // Kiểm tra có khách hàng không
        if (!$customer) {
            \Log::info('POINT_DEBUG: No customer found for order', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'customer_id' => $order->customer_id,
            ]);
            return false;
        }

        // Chỉ tích điểm cho khách hàng (role = 0)
        if ($customer->role !== 0) {
            \Log::info('POINT_DEBUG: Customer role is not 0', [
                'order_id' => $order->id,
                'customer_role' => $customer->role,
            ]);
            return false;
        }

        // Chỉ tích điểm khi đơn hàng hoàn thành và đã thanh toán
        if ($order->status !== 'completed' || $order->pay_status !== '1') {
            \Log::info('POINT_DEBUG: Order status not completed or not paid', [
                'order_id' => $order->id,
                'status' => $order->status,
                'pay_status' => $order->pay_status,
            ]);
            return false;
        }

        // Kiểm tra xem đã tích điểm cho đơn hàng này chưa
        $existingTransaction = \App\Models\PointTransaction::where('order_id', $order->id)
            ->where('type', 'earn')
            ->first();

        if ($existingTransaction) {
            \Log::info('POINT_DEBUG: earnPointsFromOrder already exists', [
                'order_id' => $order->id,
            ]);
            return false; // Đã tích điểm rồi
        }

        // Tính số điểm sẽ được tích
        $points = $this->calculateEarnedPoints($order->total);
        
        if ($points <= 0) {
            \Log::info('POINT_DEBUG: earnPointsFromOrder points <= 0', [
                'order_id' => $order->id,
                'points' => $points
            ]);
            return false;
        }

        // Cộng điểm cho khách hàng
        $customer->addPoints(
            $points,
            'earn',
            "Tích điểm từ đơn hàng #{$order->id}",
            $order->id
        );

        \Log::info('POINT_DEBUG: earnPointsFromOrder success', [
            'order_id' => $order->id,
            'points' => $points,
            'customer_points' => $customer->points,
        ]);

        return $points;
    }

    /**
     * Sử dụng điểm để giảm giá đơn hàng
     */
    public function usePointsForOrder(User $user, $pointsToUse, Order $order = null)
    {
        // Kiểm tra hệ thống điểm có được bật không
        if (!PointSetting::isPointsSystemEnabled()) {
            throw new \Exception('Hệ thống tích điểm đang tạm khóa');
        }

        // Kiểm tra user có đủ điểm không
        if (!$user->canUsePoints($pointsToUse)) {
            throw new \Exception('Không đủ điểm để sử dụng');
        }

        // Tính số tiền giảm giá
        $discountAmount = $this->calculateDiscountFromPoints($pointsToUse);

        // Trừ điểm từ tài khoản user
        $user->usePoints(
            $pointsToUse,
            "Sử dụng điểm giảm giá đơn hàng" . ($order ? " #{$order->id}" : ""),
            $order ? $order->id : null
        );

        return $discountAmount;
    }

    /**
     * Kiểm tra và tính toán điểm có thể sử dụng cho đơn hàng
     */
    public function getAvailablePointsForOrder(User $user, $orderTotal)
    {
        if (!PointSetting::isPointsSystemEnabled()) {
            return [
                'can_use' => false,
                'available_points' => 0,
                'max_points' => 0,
                'discount_amount' => 0,
                'message' => 'Hệ thống tích điểm đang tạm khóa'
            ];
        }

        $minPoints = PointSetting::getMinPointsToUse();
        
        if ($user->points < $minPoints) {
            return [
                'can_use' => false,
                'available_points' => $user->points,
                'max_points' => 0,
                'discount_amount' => 0,
                'message' => "Cần tối thiểu {$minPoints} điểm để sử dụng"
            ];
        }

        $maxPoints = $user->getMaxPointsCanUse($orderTotal);
        $discountAmount = $this->calculateDiscountFromPoints($maxPoints);

        return [
            'can_use' => true,
            'available_points' => $user->points,
            'max_points' => $maxPoints,
            'discount_amount' => $discountAmount,
            'message' => "Có thể sử dụng tối đa {$maxPoints} điểm (giảm {$discountAmount}đ)"
        ];
    }

    /**
     * Lấy lịch sử giao dịch điểm của user
     */
    public function getUserPointHistory(User $user, $limit = 20)
    {
        return $user->pointTransactions()
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Điều chỉnh điểm cho user (dành cho admin)
     */
    public function adjustUserPoints(User $user, $points, $description, $adminId)
    {
        if ($points == 0) {
            throw new \Exception('Số điểm điều chỉnh không được bằng 0');
        }

        $user->addPoints(
            $points,
            'adjust',
            $description,
            null,
            $adminId
        );

        return $user;
    }

    /**
     * Hoàn điểm khi hủy đơn hàng
     */
    public function refundPointsFromOrder(Order $order)
    {
        \Log::info('POINT_DEBUG: refundPointsFromOrder called', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'customer_id' => $order->customer_id,
            'user_role' => $order->user ? $order->user->role : null,
            'status' => $order->status,
            'pay_status' => $order->pay_status,
        ]);

        // Xác định khách hàng để hoàn điểm
        $customer = null;
        if ($order->customer_id) {
            // Đơn tại quầy có customer_id
            $customer = \App\Models\User::find($order->customer_id);
        } elseif ($order->user_id && $order->user && $order->user->role === 0) {
            // Đơn online có user_id là khách hàng
            $customer = $order->user;
        }

        // Kiểm tra có khách hàng không
        if (!$customer) {
            \Log::info('POINT_DEBUG: No customer found for refund', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'customer_id' => $order->customer_id,
            ]);
            return false;
        }

        // Chỉ hoàn điểm cho khách hàng (role = 0)
        if ($customer->role !== 0) {
            \Log::info('POINT_DEBUG: Customer role is not 0 for refund', [
                'order_id' => $order->id,
                'customer_role' => $customer->role,
            ]);
            return false;
        }

        // Tìm transaction sử dụng điểm cho đơn hàng này
        $spendTransaction = PointTransaction::where('order_id', $order->id)
            ->where('type', 'spend')
            ->where('user_id', $customer->id)
            ->first();

        if (!$spendTransaction) {
            // Kiểm tra tất cả transaction của đơn hàng này để debug
            $allTransactions = PointTransaction::where('order_id', $order->id)->get();
            \Log::info('POINT_DEBUG: No spend transaction found for refund', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'all_transactions_count' => $allTransactions->count(),
                'all_transactions' => $allTransactions->map(function($t) {
                    return [
                        'id' => $t->id,
                        'user_id' => $t->user_id,
                        'points' => $t->points,
                        'type' => $t->type,
                        'description' => $t->description
                    ];
                })->toArray()
            ]);
            return false; // Không có sử dụng điểm
        }

        $pointsToRefund = abs($spendTransaction->points);

        // Kiểm tra xem đã hoàn điểm cho đơn hàng này chưa
        $existingRefundTransaction = PointTransaction::where('order_id', $order->id)
            ->where('type', 'adjust')
            ->where('description', 'like', '%Hoàn điểm do hủy đơn hàng%')
            ->first();

        if ($existingRefundTransaction) {
            \Log::info('POINT_DEBUG: Refund already exists', [
                'order_id' => $order->id,
            ]);
            return false; // Đã hoàn điểm rồi
        }

        // Hoàn điểm cho khách hàng
        $customer->addPoints(
            $pointsToRefund,
            'adjust',
            "Hoàn điểm do hủy đơn hàng #{$order->id}",
            $order->id
        );

        \Log::info('POINT_DEBUG: refundPointsFromOrder success', [
            'order_id' => $order->id,
            'points_refunded' => $pointsToRefund,
            'customer_points' => $customer->points,
        ]);

        return $pointsToRefund;
    }

    /**
     * Hoàn điểm tích lũy khi hủy đơn hàng (nếu đơn đã được tích điểm)
     */
    public function refundEarnedPointsFromOrder(Order $order)
    {
        \Log::info('POINT_DEBUG: refundEarnedPointsFromOrder called', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'customer_id' => $order->customer_id,
        ]);

        // Xác định khách hàng để hoàn điểm
        $customer = null;
        if ($order->customer_id) {
            // Đơn tại quầy có customer_id
            $customer = \App\Models\User::find($order->customer_id);
        } elseif ($order->user_id && $order->user && $order->user->role === 0) {
            // Đơn online có user_id là khách hàng
            $customer = $order->user;
        }

        // Kiểm tra có khách hàng không
        if (!$customer) {
            \Log::info('POINT_DEBUG: No customer found for earned points refund', [
                'order_id' => $order->id,
            ]);
            return false;
        }

        // Tìm transaction tích điểm cho đơn hàng này
        $earnTransaction = PointTransaction::where('order_id', $order->id)
            ->where('type', 'earn')
            ->where('user_id', $customer->id)
            ->first();

        if (!$earnTransaction) {
            \Log::info('POINT_DEBUG: No earn transaction found for refund', [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
            ]);
            return false; // Không có tích điểm
        }

        $pointsToRefund = $earnTransaction->points;

        // Kiểm tra xem đã hoàn điểm tích lũy cho đơn hàng này chưa
        $existingRefundTransaction = PointTransaction::where('order_id', $order->id)
            ->where('type', 'adjust')
            ->where('description', 'like', '%Hoàn điểm tích lũy do hủy đơn hàng%')
            ->first();

        if ($existingRefundTransaction) {
            \Log::info('POINT_DEBUG: Earned points refund already exists', [
                'order_id' => $order->id,
            ]);
            return false; // Đã hoàn điểm rồi
        }

        // Hoàn điểm tích lũy cho khách hàng (trừ điểm đã tích)
        $customer->addPoints(
            -$pointsToRefund, // Trừ điểm
            'adjust',
            "Hoàn điểm tích lũy do hủy đơn hàng #{$order->id}",
            $order->id
        );

        \Log::info('POINT_DEBUG: refundEarnedPointsFromOrder success', [
            'order_id' => $order->id,
            'points_refunded' => $pointsToRefund,
            'customer_points' => $customer->points,
        ]);

        return $pointsToRefund;
    }

    public static function isPointsSystemEnabled()
    {
        return \DB::table('point_settings')->where('key', 'enable_points_system')->value('value') == '1';
    }
} 