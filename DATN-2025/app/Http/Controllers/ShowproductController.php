<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\sanpham;
use Illuminate\Http\Request;

class ShowproductController extends Controller
{
    public function showctsp($id)
    {
         $sanpham = sanpham::with('product_images.size', 'product_images.topping')->findOrFail($id);

        return view('client.product-single', compact('sanpham'));
    }
    public function product_images() {
    return $this->hasMany(ProductImage::class, 'product_id');
}

}
