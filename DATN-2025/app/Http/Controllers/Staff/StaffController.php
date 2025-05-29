<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\DanhMuc;

class StaffController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }

    public function index()
    {   
        return redirect()->route('staff.products');
    }

    public function products()
{
    $danhmuc = DanhMuc::all();
    $sanpham = SanPham::with('sizes')->withMin('sizes', 'price')->get();

    $donhangs = Order::with('details')
        ->whereDate('created_at', Carbon::today())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('staff.menu', compact('sanpham', 'danhmuc', 'donhangs'));
}


    public function productsByCategory($id)
    {
        $selectedDanhmuc = DanhMuc::find($id);
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        $danhmuc = DanhMuc::all();
        $sanpham = SanPham::with('sizes')->withMin('sizes', 'price')->where('id_danhmuc', $id)->get();

        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc'));
    }

    public function order_detailtoday()
{
    $donhangs = Order::with('details')
        ->whereDate('created_at', Carbon::today())
        ->orderBy('created_at', 'desc')
        ->get();

    $danhmuc = DanhMuc::all();
    $sanpham = SanPham::all();

    return view('staff.order_detail', compact('donhangs', 'danhmuc', 'sanpham'));

}

}
