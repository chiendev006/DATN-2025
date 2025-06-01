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

        return view('client.product-single', compact('sanpham','topping', 'product','comment'));
    }
}
