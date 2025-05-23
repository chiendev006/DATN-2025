<?php

namespace App\Http\Controllers;

use App\Models\sanpham;
use App\Models\Danhmuc;
use App\Models\ProductAttribute;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(){
        return view('client.home');
    }
public function  danhmuc()
{
    $danhmucs = Danhmuc::with('sanphams')->get();
    $sanpham = sanpham::take(4)->get();

    // Gán giá nhỏ nhất cho từng sản phẩm
    foreach ($sanpham as $sp) {
        $minPrice = Size::where('product_id', $sp->id)->min('price');
        $sp->min_price = $minPrice;
    }

    return view('client.home', compact('danhmucs', 'sanpham'));
}
public function show()
{
    $danhmucs = Danhmuc::with('sanphams')->get();

    // Gán giá nhỏ nhất cho từng sản phẩm trong từng danh mục
    foreach ($danhmucs as $danhmuc) {
        foreach ($danhmuc->sanphams as $sp) {
            $minPrice = Size::where('product_id', $sp->id)->min('price');
            $sp->min_price = $minPrice;
        }
    }

    return view('client.menu', compact('danhmucs'));
}
public function  showsp()
{
    $danhmucs = Danhmuc::with('sanphams')->get();
    $sanpham = sanpham::take(4)->get();

    foreach ($sanpham as $sp) {
        $minPrice = Size::where('product_id', $sp->id)->min('price');
        $sp->min_price = $minPrice;
    }

    return view('client.menu', compact('danhmucs', 'sanpham'));
}
    public function search(Request $request){
    $keyword = $request->input('search');
    $sanpham = sanpham::where('name', 'LIKE', '%' . $keyword . '%')->get();
    return view('client.search', compact('sanpham', 'keyword'));
}
}
