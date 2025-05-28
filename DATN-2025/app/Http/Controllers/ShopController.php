<?php

namespace App\Http\Controllers;

use App\Models\Danhmuc;
use App\Models\Size;
use Illuminate\Http\Request;

class ShopController extends Controller
{
  public function index()
    {
        $danhmucs = Danhmuc::with(['sanphams' => function($query) {
            $query->withMin('sizes', 'price');
        }])->get();

        $firstDanhmuc = $danhmucs->first();
        $firstProducts = $firstDanhmuc ? $firstDanhmuc->sanphams : [];

        foreach ($firstProducts as $sp) {
            $sp->min_price = $sp->sizes_min_price ?? 0;
        }

        return view('client.shop', compact('danhmucs', 'firstDanhmuc', 'firstProducts'));
    }

    public function getByCategory($id)
    {
        $danhmuc = Danhmuc::with('sanphams')->findOrFail($id);

        foreach ($danhmuc->sanphams as $sp) {
            $sp->min_price = Size::where('product_id', $sp->id)->min('price');
        }

        return response()->json([
            'products' => $danhmuc->sanphams,
            'category_name' => $danhmuc->name,
        ]);
    }
    
}
