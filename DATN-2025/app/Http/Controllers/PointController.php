<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointSetting;
use App\Services\PointService;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Lấy thông tin điểm của user hiện tại
     */
    public function getUserPoints()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $pointInfo = $this->pointService->getAvailablePointsForOrder($user, 0);

        return response()->json([
            'points' => $user->points,
            'formatted_points' => $user->formatted_points,
            'can_use' => $pointInfo['can_use'],
            'min_points' => PointSetting::getMinPointsToUse(),
            'points_value' => PointSetting::getVndPerPoint() . 'đ/điểm'
        ]);
    }

    /**
     * Lấy thông tin điểm có thể sử dụng cho đơn hàng
     */
    public function getAvailablePointsForOrder(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $request->validate([
            'order_total' => 'required|numeric|min:0'
        ]);

        $orderTotal = $request->order_total;
        $pointInfo = $this->pointService->getAvailablePointsForOrder($user, $orderTotal);

        return response()->json($pointInfo);
    }

    /**
     * Lấy lịch sử giao dịch điểm
     */
    public function getPointHistory(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $limit = $request->get('limit', 20);
        $history = $this->pointService->getUserPointHistory($user, $limit);

        return response()->json([
            'history' => $history,
            'total_points' => $user->points
        ]);
    }

    /**
     * Tính toán giảm giá từ điểm sử dụng
     */
    public function calculateDiscount(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $request->validate([
            'points_to_use' => 'required|integer|min:1',
            'order_total' => 'required|numeric|min:0'
        ]);

        $pointsToUse = $request->points_to_use;
        $orderTotal = $request->order_total;

        try {
            // Kiểm tra có thể sử dụng điểm không
            if (!$user->canUsePoints($pointsToUse)) {
                return response()->json([
                    'error' => 'Không đủ điểm để sử dụng'
                ], 400);
            }

            // Kiểm tra không vượt quá giới hạn
            $maxPoints = $user->getMaxPointsCanUse($orderTotal);
            if ($pointsToUse > $maxPoints) {
                return response()->json([
                    'error' => "Chỉ có thể sử dụng tối đa {$maxPoints} điểm cho đơn hàng này"
                ], 400);
            }

            // Tính giảm giá
            $discountAmount = $this->pointService->calculateDiscountFromPoints($pointsToUse);
            $finalTotal = $orderTotal - $discountAmount;

            return response()->json([
                'points_used' => $pointsToUse,
                'discount_amount' => $discountAmount,
                'order_total' => $orderTotal,
                'final_total' => $finalTotal,
                'remaining_points' => $user->points - $pointsToUse
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Lấy cấu hình hệ thống điểm (public)
     */
    public function getPointSettings()
    {
        return response()->json([
            'points_per_vnd' => PointSetting::getPointsPerVnd(),
            'vnd_per_point' => PointSetting::getVndPerPoint(),
            'min_points_to_use' => PointSetting::getMinPointsToUse(),
            'max_points_per_order' => PointSetting::getMaxPointsPerOrder(),
            'is_enabled' => PointSetting::isPointsSystemEnabled()
        ]);
    }
} 