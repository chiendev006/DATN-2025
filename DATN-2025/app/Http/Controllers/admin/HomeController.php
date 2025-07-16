<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Models\Order;
use App\Models\Orderdetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // 12 tháng gần nhất
        $months = collect();
        $ordersPerMonth = [];
        $revenuePerMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $months->push($month);

            // Đếm tất cả đơn trong tháng
            $ordersPerMonth[] = Order::whereYear('created_at', Carbon::parse($month)->year)
                ->whereMonth('created_at', Carbon::parse($month)->month)
                ->count();

            // Tổng doanh thu đơn đã thanh toán trong tháng
            $revenuePerMonth[] = Order::whereYear('created_at', Carbon::parse($month)->year)
                ->whereMonth('created_at', Carbon::parse($month)->month)
                ->where('pay_status', '1')
                ->sum('total');
        }

        // Đơn trong tuần này
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $success = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'completed')->count();
        $pending = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['pending', 'processing', 'shipping'])->count();
        $cancel = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'cancelled')->count();

        // Pie chart: Xu hướng khách hàng
        // Lấy tất cả user_id là null hoặc user_id có role=21 hoặc role=1
        $today = \Carbon\Carbon::today();
        $tomorrow = \Carbon\Carbon::tomorrow();
        $orders = Order::where('created_at', '>=', $today)
            ->where('created_at', '<', $tomorrow)
            ->get();
        $userIds = $orders->pluck('user_id')->filter()->unique();
        $role21And1Ids = [];
        if ($userIds->count() > 0) {
            $role21And1Ids = \App\Models\User::whereIn('id', $userIds)
                ->where(function($query) {
                    $query->where('role', 21)->orWhere('role', 1);  
                })
                ->pluck('id')
                ->toArray();
        }
        $muaThang = $orders->where(function($order) use ($role21And1Ids) {
            return is_null($order->user_id) || in_array($order->user_id, $role21And1Ids);
        })->count();
        $muaTaiKhoan = $orders->where(function($order) use ($role21And1Ids) {
            return !is_null($order->user_id) && !in_array($order->user_id, $role21And1Ids);
        })->count();

        $recentOrders = Order::orderBy('created_at', 'desc')->limit(5)->get();
         $topProducts = Orderdetail::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
        ->groupBy('product_id')
        ->orderByDesc('total_quantity')
        ->take(5)
        ->with('product') // eager load nếu có quan hệ với model Product
        ->get();
        return view('admin.home', [
            'months' => $months,
            'ordersPerMonth' => $ordersPerMonth,
            'revenuePerMonth' => $revenuePerMonth,
            'orderStatusWeek' => [
                'success' => $success,
                'pending' => $pending,
                'cancel' => $cancel,
            ],
            'muaThang' => $muaThang,
            'muaTaiKhoan' => $muaTaiKhoan,
            'recentOrders' => $recentOrders,
            'topproduct'=>$topProducts
        ]);
    }

    public function filterRevenue(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = date('Y-m-d 23:59:59', strtotime($request->end_date));

        // Tổng hợp toàn bộ khoảng thời gian
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedCount = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count();
        $cancelledCount = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->where('pay_status', '1')->sum('total');

        // Gửi về 1 dòng tổng hợp
        $revenueData = [[
            'date_range' => 'Từ ' . date('d/m/Y', strtotime($startDate)) . ' đến ' . date('d/m/Y', strtotime($request->end_date)),
            'total_orders' => $totalOrders,
            'completed_count' => $completedCount,
            'cancelled_count' => $cancelledCount,
            'revenue' => $totalRevenue,
        ]];

        // Top khách hàng (role != 1, 21, 22)
        $topCustomer = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->whereHas('user', function($q) {
                $q->whereNotIn('role', [1, 21, 22]);
            })
            ->select('user_id', DB::raw('SUM(total) as total_spent'))
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->first();

        // Top nhân viên (role = 21)
        $topStaff = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->whereHas('user', function($q) {
                $q->where('role', 21);
            })
            ->select('user_id', DB::raw('SUM(total) as total_revenue'))
            ->groupBy('user_id')
            ->orderByDesc('total_revenue')
            ->with('user')
            ->first();

        // Top sản phẩm bán chạy
        $topProducts = \App\Models\Orderdetail::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('pay_status', '1'); // Chỉ đơn đã thanh toán
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->limit(5)
            ->get();

        // Thống kê mã giảm giá
        $coupons = \App\Models\Coupon::with(['orders' => function($q) use ($startDate, $endDate) {
            $q->where('pay_status', '1') // Chỉ đơn đã thanh toán
              ->whereBetween('orders.created_at', [$startDate, $endDate]);
        }])->get();
        $couponStats = [];
        foreach ($coupons as $coupon) {
            $usedCount = $coupon->orders->count();
            $totalDiscount = $coupon->orders->sum('coupon_total_discount');
            $totalRevenue = $coupon->orders->sum('total');
            $couponStats[] = [
                'code' => $coupon->code,
                'used_count' => $usedCount,
                'total_discount' => $totalDiscount,
                'total_revenue' => $totalRevenue,
                'expires_at' => $coupon->expires_at ?? null,
                'max_uses' => $coupon->max_uses ?? null,
            ];
        }

        // Thống kê điểm thưởng chi tiết
        $pointStats = [];
        
        // 1. Thống kê tổng quan
        $totalEarned = \App\Models\PointTransaction::where('type', 'earn')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('points');
        $totalSpent = abs(\App\Models\PointTransaction::where('type', 'spend')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('points'));
        $totalAdjusted = \App\Models\PointTransaction::where('type', 'adjust')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('points');
        $totalExpired = abs(\App\Models\PointTransaction::where('type', 'expire')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('points'));
        
        // 2. Số lượng người dùng tham gia
        $usersEarnedPoints = \App\Models\PointTransaction::where('type', 'earn')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()->count('user_id');
        $usersUsedPoints = \App\Models\PointTransaction::where('type', 'spend')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()->count('user_id');
        $usersAdjustedPoints = \App\Models\PointTransaction::where('type', 'adjust')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()->count('user_id');
        
        // 3. Top người tích điểm nhiều nhất
        $topEarners = \App\Models\PointTransaction::where('type', 'earn')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('SUM(points) as total_earned'))
            ->groupBy('user_id')
            ->orderByDesc('total_earned')
            ->with('user')
            ->limit(5)
            ->get();
        
        // 4. Top người sử dụng điểm nhiều nhất
        $topSpenders = \App\Models\PointTransaction::where('type', 'spend')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('SUM(ABS(points)) as total_spent'))
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->limit(5)
            ->get();
        
        // 5. Thống kê theo ngày (có phân trang)
        $dailyPage = $request->input('daily_page', 1);
        $dailyPointStats = \App\Models\PointTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type = "earn" THEN points ELSE 0 END) as earned'),
                DB::raw('SUM(CASE WHEN type = "spend" THEN ABS(points) ELSE 0 END) as spent'),
                DB::raw('SUM(CASE WHEN type = "adjust" THEN points ELSE 0 END) as adjusted'),
                DB::raw('SUM(CASE WHEN type = "expire" THEN ABS(points) ELSE 0 END) as expired'),
                DB::raw('COUNT(CASE WHEN type = "earn" THEN 1 END) as earn_transactions'),
                DB::raw('COUNT(CASE WHEN type = "spend" THEN 1 END) as spend_transactions')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15, ['*'], 'daily_page', $dailyPage); // Phân trang 15 bản ghi mỗi trang
        
        // 6. Thống kê theo loại giao dịch
        $transactionTypeStats = \App\Models\PointTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'type',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(CASE WHEN type IN ("spend", "expire") THEN ABS(points) ELSE points END) as total_points'),
                DB::raw('AVG(CASE WHEN type IN ("spend", "expire") THEN ABS(points) ELSE points END) as avg_points')
            )
            ->groupBy('type')
            ->get();
        
        // 7. Thống kê điểm hiện tại của tất cả user
        $currentPointsStats = \App\Models\User::where('role', 0) // Chỉ khách hàng
            ->select(
                DB::raw('COUNT(*) as total_users'),
                DB::raw('SUM(points) as total_current_points'),
                DB::raw('AVG(points) as avg_points_per_user'),
                DB::raw('COUNT(CASE WHEN points > 0 THEN 1 END) as users_with_points'),
                DB::raw('COUNT(CASE WHEN points = 0 THEN 1 END) as users_without_points')
            )
            ->first();
        
        // 8. Giao dịch điểm gần đây (có phân trang)
        $recentPage = $request->input('recent_page', 1);
        $recentPointTransactions = \App\Models\PointTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'order'])
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'recent_page', $recentPage); // Phân trang 10 bản ghi mỗi trang
        
        // 9. Thống kê hiệu quả sử dụng điểm
        $pointsEfficiency = [
            'total_earned' => $totalEarned,
            'total_spent' => $totalSpent,
            'total_adjusted' => $totalAdjusted,
            'total_expired' => $totalExpired,
            // Giới hạn usage_rate tối đa 100%
            'usage_rate' => $totalEarned > 0 ? min(round(($totalSpent / $totalEarned) * 100, 2), 100) : 0,
            'users_earned' => $usersEarnedPoints,
            'users_used' => $usersUsedPoints,
            'users_adjusted' => $usersAdjustedPoints,
            'current_stats' => $currentPointsStats,
            'top_earners' => $topEarners,
            'top_spenders' => $topSpenders,
            'daily_stats' => $dailyPointStats,
            'type_stats' => $transactionTypeStats,
            'recent_transactions' => $recentPointTransactions,
            // Thêm thống kê bổ sung
            'total_transactions' => \App\Models\PointTransaction::whereBetween('created_at', [$startDate, $endDate])->count(),
            'avg_points_per_transaction' => \App\Models\PointTransaction::whereBetween('created_at', [$startDate, $endDate])->avg('points'),
            'points_per_user_avg' => $usersEarnedPoints > 0 ? round($totalEarned / $usersEarnedPoints, 2) : 0,
            'spend_per_user_avg' => $usersUsedPoints > 0 ? round($totalSpent / $usersUsedPoints, 2) : 0
        ];

        return response()->json([
            'revenueData' => $revenueData,
            'topCustomer' => $topCustomer,
            'topStaff' => $topStaff,
            'topProducts' => $topProducts,
            'couponStats' => $couponStats,
            'pointsEfficiency' => $pointsEfficiency,
        ]);
    }

    public function customerTrendFilter(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        if (!$start || !$end) {
            return response()->json(['error' => 'Thiếu ngày bắt đầu hoặc kết thúc'], 400);
        }
        $startDate = \Carbon\Carbon::parse($start)->startOfDay();
        $endDate = \Carbon\Carbon::parse($end)->endOfDay();
        $orders = \App\Models\Order::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();
        $userIds = $orders->pluck('user_id')->filter()->unique();
        $role21And1Ids = [];
        if ($userIds->count() > 0) {
            $role21And1Ids = \App\Models\User::whereIn('id', $userIds)
                ->where(function($query) {
                    $query->where('role', 21)->orWhere('role', 1);
                })
                ->pluck('id')
                ->toArray();
        }
        $muaThang = $orders->where(function($order) use ($role21And1Ids) {
            return is_null($order->user_id) || in_array($order->user_id, $role21And1Ids);
        })->count();
        $muaTaiKhoan = $orders->where(function($order) use ($role21And1Ids) {
            return !is_null($order->user_id) && !in_array($order->user_id, $role21And1Ids);
        })->count();
        return response()->json([
            'muaThang' => $muaThang,
            'muaTaiKhoan' => $muaTaiKhoan
        ]);
    }
}
