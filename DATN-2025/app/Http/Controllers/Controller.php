<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Product_comment;
use App\Models\sanpham;
use App\Models\Danhmuc;
use App\Models\ProductAttribute;
use App\Models\Sanphams;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function home(){
        return view('client.home');
    }
public function  danhmuc()
{
    $danhmucs = Danhmuc::with('sanphams')->get();
    $sanpham = sanpham::take(8)->get();
    $blog = Blogs::all();
    foreach ($sanpham as $sp) {
        $minPrice = Size::where('product_id', $sp->id)->min('price');
        $sp->min_price = $minPrice;
    }
    return view('client.home', compact('danhmucs', 'sanpham', 'blog'));
}
 public function show(Request $request)
    {
        $danhmucs = Danhmuc::with(['sanphams' => function($query) {
            $query->withMin('sizes', 'price');
        }])->get();

        $categoryId = $request->query('category');
        $firstDanhmuc = $categoryId
            ? $danhmucs->firstWhere('id', $categoryId)
            : $danhmucs->first();

        $perPage = 10; 
        $firstProducts = $firstDanhmuc
            ? $firstDanhmuc->sanphams()->withMin('sizes', 'price')->paginate($perPage)
            : collect([])->paginate($perPage);

        foreach ($firstProducts as $sp) {
            $sp->min_price = $sp->sizes_min_price ?? 0;
        }

        return view('client.menu', compact('danhmucs', 'firstDanhmuc', 'firstProducts'));
    }

    public function getCategoryProducts(Request $request, $categoryId)
    {
        $danhmuc = Danhmuc::findOrFail($categoryId);
        $perPage = 8; 
        $products = $danhmuc->sanphams()->withMin('sizes', 'price')->paginate($perPage);

        foreach ($products as $product) {
            $product->min_price = $product->sizes_min_price ?? 0;
        }

        return response()->json([
            'category_name' => $danhmuc->name,
            'category_description' => $danhmuc->description,
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ]
        ]);
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
public function ajaxSearch(Request $request)
{
    $keyword = $request->input('search');
    $sanphams = Sanpham::with('sizes')
        ->where('name', 'LIKE', '%' . $keyword . '%')
        ->get();
    $data = $sanphams->map(function ($item) {
        $minPrice = $item->sizes->min('price');
        return [
            'id' => $item->id,
            'name' => $item->name,
            'image' => asset('storage/' . ltrim($item->image, '/')),
            'min_price' => $minPrice ?? 0,
        ];
    });
    return response()->json(['sanpham' => $data]);
}
public function filterByPrice(Request $request)
{
    $min = (int) $request->min;
    $max = (int) $request->max;
    $page = (int) $request->input('page', 1);
    $perPage = 12;

    $query = Sanpham::whereHas('attributes', function($q) use ($min, $max) {
        $q->whereBetween('price', [$min, $max]);
    })
    ->with(['attributes' => function($q) use ($min, $max) {
        $q->whereBetween('price', [$min, $max]);
    }]);

    $products = $query->paginate($perPage, ['*'], 'page', $page);

    $data = $products->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'image' => asset('storage/' . (str_contains($item->image, 'uploads/') ? $item->image : 'uploads/' . $item->image)),
            'min_price' => $item->attributes->min('price') ?? 0,
        ];
    });

    return response()->json([
        'products' => $data,
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'total' => $products->total(),
        'per_page' => $products->perPage(),
    ]);
}
public function postComment(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:sanphams,id',
        'comment' => 'required|string',
        'rating' => 'required|integer|min:1|max:5'
    ]);

    Product_comment::create([
        'user_id' => Auth::id(),
        'product_id' => $request->product_id,
        'comment' => $request->comment,
        'rating' => $request->rating,
    ]);

    return back();
}

}
