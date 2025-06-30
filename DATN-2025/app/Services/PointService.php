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
            'user_role' => $order->user ? $order->user->role : null,
            'status' => $order->status,
            'pay_status' => $order->pay_status,
        ]);
        // Chỉ tích điểm cho khách hàng (role = 0)
        if ($order->user->role !== 0) {
            return false;
        }

        // Chỉ tích điểm khi đơn hàng hoàn thành và đã thanh toán
        if ($order->status !== 'completed' || $order->pay_status !== '1') {
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

        // Cộng điểm cho user
        $order->user->addPoints(
            $points,
            'earn',
            "Tích điểm từ đơn hàng #{$order->id}",
            $order->id
        );

        \Log::info('POINT_DEBUG: earnPointsFromOrder success', [
            'order_id' => $order->id,
            'points' => $points,
            'user_points' => $order->user->points,
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
        // Tìm transaction sử dụng điểm cho đơn hàng này
        $spendTransaction = PointTransaction::where('order_id', $order->id)
            ->where('type', 'spend')
            ->first();

        if (!$spendTransaction) {
            return false; // Không có sử dụng điểm
        }

        $pointsToRefund = abs($spendTransaction->points);

        // Hoàn điểm cho user
        $order->user->addPoints(
            $pointsToRefund,
            'adjust',
            "Hoàn điểm do hủy đơn hàng #{$order->id}",
            null
        );

        return $pointsToRefund;
    }
} 