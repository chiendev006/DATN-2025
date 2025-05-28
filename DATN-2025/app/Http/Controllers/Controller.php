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

public function show(Request $request)
{
    $danhmucs = Danhmuc::with(['sanphams' => function($query) {
        $query->withMin('sizes', 'price');
    }])->get();

    // Xác định danh mục cần hiển thị
    $categoryId = $request->query('category');
    $firstDanhmuc = $categoryId
        ? $danhmucs->firstWhere('id', $categoryId)
        : $danhmucs->first();

    $firstProducts = $firstDanhmuc ? $firstDanhmuc->sanphams : [];

    foreach ($firstProducts as $sp) {
        $sp->min_price = $sp->sizes_min_price ?? 0;
    }

    return view('client.menu', compact('danhmucs', 'firstDanhmuc', 'firstProducts'));
}



    public function getProductsByCategory($id)
    {
        $danhmuc = Danhmuc::with('sanphams')->findOrFail($id);

        foreach ($danhmuc->sanphams as $sp) {
            $minPrice = Size::where('product_id', $sp->id)->min('price');
            $sp->min_price = $minPrice;
        }

        return response()->json([
            'category_name' => $danhmuc->name,
            'category_description' => $danhmuc->description,
            'category_image' => $danhmuc->image ?? '/asset/images/item15.png',
            'products' => $danhmuc->sanphams
        ]);
    }

    public function search(Request $request){
    $keyword = $request->input('search');
    $sanpham = sanpham::where('name', 'LIKE', '%' . $keyword . '%')->get();
    return view('client.search', compact('sanpham', 'keyword'));
}

}
