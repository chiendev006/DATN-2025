<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\admin\Product_attributesController;
use App\Http\Controllers\Controller;
use App\Models\Product_topping;
use App\Models\Size;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\Topping;
use App\Models\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $message = session('message');
        return redirect()->route('staff.products'); // chuyển thẳng về trang sản phẩm
    }

    public function getOptions($id)
    {
        $products = SanPham::all();
        $attributes = Size::where('product_id', $id)->get(['size', 'price']);
        $toppings = Product_topping::where('product_id', $id)->get(['topping', 'price']);
        return response()->json([
            'attributes' => $attributes,
            'toppings' => $toppings
        ], 200);
    }

    public function products()
    {
        $sanpham = SanPham::all();
        $danhmuc = DanhMuc::all();
        $message = session('message');
        return view('staff.menu', compact('sanpham', 'danhmuc', 'message'));
    }

    public function productsByCategory($id)
    {
        $sanpham = SanPham::where('id_danhmuc', $id)->get();
        $danhmuc = DanhMuc::all();
        $selectedDanhmuc = DanhMuc::find($id);
        $message = session('message');
        if (!$selectedDanhmuc) {
            return redirect()->route('staff.products')->with('error', 'Danh mục không tồn tại.');
        }
        return view('staff.menu', compact('sanpham', 'danhmuc', 'selectedDanhmuc', 'message'));
    }
     public function orderdetailtoday()
    {
        $donhangs = Order::with('details')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $danhmuc = DanhMuc::all();
        $sanpham = SanPham::all();

        return view('staff.orderdetail', compact('donhangs', 'danhmuc', 'sanpham'));

    }
    public function searchProducts(Request $request)
    {
        $danhmuc = DanhMuc::all();
        $keyword = trim($request->input('keyword'));

        if (!empty($keyword)) {
            $sanpham = Sanpham::where('name', 'like', "%$keyword%")
                        ->orWhere('mota', 'like', "%$keyword%")
                        ->get();
        } else {
            $sanpham = collect(); // hoặc Sanpham::all() nếu muốn hiện tất cả
        }

        return view('staff.menu', compact('sanpham', 'keyword' , 'danhmuc'));
    }
}
