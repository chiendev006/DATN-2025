<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;
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
        return view('staff.menu', compact('sanpham', 'danhmuc'));
    }

    public function productsByCategory($id)
    {
        $sanpham = SanPham::where('id_danhmuc', $id)->get();
        $danhmuc = DanhMuc::all();
        $selectedDanhmuc = DanhMuc::find($id);
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc'));
    }
}
