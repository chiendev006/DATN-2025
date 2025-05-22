<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\sanpham;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{


    public function create()
    {
        $sanpham = sanpham::all();
        return view('admin.product_img.image', compact('sanpham'));
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $fileName = uniqid() . $file->getClientOriginalName();
                $file->storeAs('public/uploads/', $fileName);
                ProductImage::create([
                    "product_id" => session('sanpham_id'),
                    'image_url' => $fileName,
                ]);
            }
        }
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
                return redirect()->route('sanpham.edit', ['id' => session('sanpham_id'), ])->with('success', 'Thêm sản phẩm và size thành công!');
    }





    public function destroy($id)
    {
        $productImage = ProductImage::findOrFail($id);

        $filePath = 'uploads/' . $productImage->image_url;
        if ($productImage->image_url && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $productImage->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('sanpham.edit', ['id' => session('sanpham_id')])->with('success', 'Thêm sản phẩm và size thành công!');
    }
}
