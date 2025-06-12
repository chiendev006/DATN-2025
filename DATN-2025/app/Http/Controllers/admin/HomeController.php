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
                ->where('pay_status', 1)
                ->sum('total');
        }

        // Đơn trong tuần này
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $ordersThisWeek = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        $success = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', 1)->count();
        $pending = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', 0)->count();
        $cancel = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('pay_status', 2)->count();

        // Pie chart: Xu hướng khách hàng
        // Lấy tất cả user_id là null hoặc user_id có role=21
        $orders = Order::all();
        $userIds = $orders->pluck('user_id')->filter()->unique();
        $role21Ids = [];
        if ($userIds->count() > 0) {
            $role21Ids = \App\Models\User::whereIn('id', $userIds)->where('role', 21)->pluck('id')->toArray();
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
}
