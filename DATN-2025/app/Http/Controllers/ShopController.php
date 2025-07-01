<?php

namespace App\Http\Controllers;

use App\Models\Danhmuc;
use App\Models\sanpham;
use App\Models\Size;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $danhmucs = Danhmuc::with(['sanphams' => function($query) {
            $query->withMin('sizes', 'price');
        }])->get();
    
        $danhmucId = $request->input('danhmuc_id', $danhmucs->first()->id ?? null);
        $page = $request->input('page', 1);
        $perPage = 12;
        $sort = $request->input('sort', '');
    
        // Nếu không chọn danh mục (hoặc chọn "Tất cả") hoặc là chuỗi 'null'
        if (empty($danhmucId) || $danhmucId === 'null') {
            $firstDanhmuc = null;
            $firstProducts = sanpham::withMin('sizes', 'price')->paginate($perPage);
        } else {
            $firstDanhmuc = $danhmucs->where('id', $danhmucId)->first();
            $firstProducts = $firstDanhmuc ? $firstDanhmuc->sanphams()->withMin('sizes', 'price')->paginate($perPage) : collect([]);
        }
    
        foreach ($firstProducts as $sp) {
            $sp->min_price = $sp->sizes_min_price ?? 0;
        }
    
        // Add best deals
        $bestDeals = sanpham::withMin('sizes as min_price', 'price')
                        ->orderBy('min_price', 'asc')
                        ->take(3)
                        ->get();
    
        if ($request->ajax()) {
            return response()->json([
                'products' => $firstProducts->items(),
                'current_page' => $firstProducts->currentPage(),
                'last_page' => $firstProducts->lastPage(),
                'total' => $firstProducts->total(),
                'per_page' => $firstProducts->perPage(),
                'danhmuc_id' => $danhmucId
            ]);
        }
    
        return view('client.shop', compact('danhmucs', 'firstDanhmuc', 'firstProducts', 'bestDeals', 'sort'));
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
