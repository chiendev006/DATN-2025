<?php

namespace App\Http\Controllers;

use App\Models\sanpham;
use App\Models\Topping;
use Illuminate\Http\Request;

class ShowproductController extends Controller
{
    public function showctsp($id)
    {
        $sanpham = sanpham::with(['product_images', 'attributes'])->findOrFail($id);
        $topping = Topping::all();

        return view('client.product-single', compact('sanpham','topping'));
    }
}