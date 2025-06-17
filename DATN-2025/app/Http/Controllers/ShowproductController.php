<?php

namespace App\Http\Controllers;

use App\Models\Product_comment;
use App\Models\sanpham;
use App\Models\Sanphams;
use App\Models\Topping;
use Illuminate\Http\Request;

class ShowproductController extends Controller
{
    public function showctsp($id)
    {
        $sanpham = sanpham::with(['product_images', 'attributes'])->findOrFail($id);
        $topping = Topping::all();

        $product = sanpham::findOrFail($id);
        $comment = Product_comment::where('product_id', $product->id)->with('user')->get();

        // Get related products from same category
        $relatedProducts = sanpham::where('id_danhmuc', $sanpham->id_danhmuc)
            ->where('id', '!=', $sanpham->id)
            ->with(['sizes' => function($query) {
                $query->select('id', 'product_id', 'price');
            }])
            ->take(4)
            ->get();

        // Add min price to each related product
        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct->min_price = $relatedProduct->sizes->min('price') ?? 0;
        }

        return view('client.product-single', compact('sanpham','topping', 'product','comment', 'relatedProducts'));
    }
}
