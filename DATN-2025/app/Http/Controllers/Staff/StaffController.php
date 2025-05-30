<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Size;
use App\Models\Product_topping;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function login()
    {
        return view('staff.login');
    }

    public function index()
    {   
        return redirect()->route('staff.products'); // chuyển thẳng về trang sản phẩm
    }

    public function products()
    {
        $sanpham = SanPham::all();
        $danhmuc = DanhMuc::all();
        $size_gia = Size::all();
        $topping = Product_topping::all();
        return view('staff.menu', compact('sanpham', 'danhmuc', 'size_gia', 'topping'));
    }
    public function productsByCategory($id)
    {
        $sanpham = SanPham::where('id_danhmuc', $id)->get();
        $danhmuc = DanhMuc::all();
        $selectedDanhmuc = DanhMuc::find($id);
        $size_gia = Size::all();
        $topping = Product_topping::all();
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc', 'size_gia', 'topping'));  
    }
    
    public function orderdetailtoday()
    {
        $donhangs = Order::with('details')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $danhmuc = DanhMuc::all();
        $sanpham = SanPham::all();

        return view('staff.orderdetailtoday', compact('donhangs', 'danhmuc', 'sanpham'));
    }
    
   public function productsDetail($id) // Sửa tên method thành camelCase
{
    $sanpham = SanPham::find($id);
    
    if (!$sanpham) {
        return redirect()->route('staff.products')->with('error', 'Sản phẩm không tồn tại.');
    }
    
    $size_gia = Size::where('product_id', $id)->get();
    $toppings = Product_topping::where('product_id', $id)->get();

    return view('staff.productdetail', compact('sanpham', 'size_gia', 'toppings'));
}

}