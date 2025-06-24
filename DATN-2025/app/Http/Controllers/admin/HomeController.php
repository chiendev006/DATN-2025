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

        $ordersThisWeek = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        $success = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', '1')->count();
        $pending = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', '0')->count();
        $cancel = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', '2')->orwhere('pay_status','3')->count();

        // Pie chart: Xu hướng khách hàng
        // Lấy tất cả user_id là null hoặc user_id có role=21
        $orders = Order::all();
        $userIds = $orders->pluck('user_id')->filter()->unique();
        $role21Ids = [];
        if ($userIds->count() > 0) {
            $role21Ids = \App\Models\User::whereIn('id', $userIds)->where('role', 21)->orwhere('role', 1)->pluck('id')->toArray();
        }
        $muaThang = $orders->where(function($order) use ($role21Ids) {
            return is_null($order->user_id) || in_array($order->user_id, $role21Ids);
        })->count();
        $muaTaiKhoan = $orders->where(function($order) use ($role21Ids) {
            return !is_null($order->user_id) && !in_array($order->user_id, $role21Ids);
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

        $revenueData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw("SUM(CASE WHEN pay_status = '1' THEN total ELSE 0 END) as revenue"),
                DB::raw("COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count"),
                DB::raw("COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_count")
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

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


        return response()->json([
            'revenueData' => $revenueData,
            'topCustomer' => $topCustomer,
            'topStaff' => $topStaff,
        ]);
    }
}
