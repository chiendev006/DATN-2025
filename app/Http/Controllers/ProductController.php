<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductsByCategory($category)
    {
        // Lấy sản phẩm theo danh mục
        $products = Product::where('category', $category)->get();
        
        // Trả về view partial chứa danh sách sản phẩm
        $view = view('partials.product-list', compact('products'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }
}