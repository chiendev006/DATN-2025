<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        $unpaid = Order::where('pay_status', 0)->count();
        $paid = Order::where('pay_status', 1)->count();
        $cancelled = Order::where('pay_status', 2)->count();

        // Lấy 12 tháng gần nhất
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        $ordersPerMonth = [];
        $revenuePerMonth = [];
        foreach ($months as $month) {
            $ordersPerMonth[] = Order::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
            $revenuePerMonth[] = Order::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->where('pay_status', 1)
                ->sum('total');
        }
        $monthLabels = $months->map(function($m) { return date('m/Y', strtotime($m.'-01')); });

        return view('admin.home', compact('unpaid', 'paid', 'cancelled', 'ordersPerMonth', 'revenuePerMonth', 'monthLabels'));
    }
}
