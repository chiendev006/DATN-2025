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
    public function index()
    {
        $product_images = ProductImage::with('sanpham')->paginate(5);
        return view('admin.bienthe.index', compact('product_images'));
    }

    public function create()
    {
        $sanpham = sanpham::all();
        return view('admin.bienthe.image', compact('sanpham'));
    }

    public function store(Request $request)
    {
        $path = $request->file('image')->store('uploads', 'public');

        ProductImage::create([
            'product_id' => $request->product_id,
            'image_url' => $path,
            'is_primary' => $request->has('is_primary'),
        ]);

        return redirect()->route('product-images.index')->with('success', 'Thêm ảnh thành công!');
    }

    public function edit($id)
    {
        $productImage = ProductImage::findOrFail($id);
        $sanpham = sanpham::all();

        return view('admin.bienthe.edit', compact('productImage', 'sanpham'));
    }

    public function update(Request $request, $id)
    {
        $productImage = ProductImage::findOrFail($id);

        $data = [
            'product_id' => $request->product_id,
            'is_primary' => $request->has('is_primary'),
        ];

        if ($request->hasFile('image')) {
            if ($productImage->image_url && Storage::disk('public')->exists($productImage->image_url)) {
                Storage::disk('public')->delete($productImage->image_url);
            }
            $path = $request->file('image')->store('uploads', 'public');
            $data['image_url'] = $path;
        }

        $productImage->update($data);

        return redirect()->route('product-images.index')->with('success', 'Cập nhật ảnh thành công!');
    }

    public function destroy($id)
    {
        $productImage = ProductImage::findOrFail($id);

        if ($productImage->image_url && Storage::disk('public')->exists($productImage->image_url)) {
            Storage::disk('public')->delete($productImage->image_url);
        }

        $productImage->delete();

        return redirect()->route('product-images.index')->with('success', 'Xóa ảnh thành công!');
    }
}
