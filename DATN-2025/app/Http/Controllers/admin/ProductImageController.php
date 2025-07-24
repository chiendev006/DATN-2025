<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\historylog;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\sanpham;
use Illuminate\Support\Facades\Auth;
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
        if ($request->hasFile('hasFile')) {
            $content1 = '';
            $count = 0;
            foreach ($request->file('hasFile') as $file) {
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploads', $fileName);
                $content1 .= "*<img src=\"" . url("/storage/uploads/$fileName") . "\" alt=\"\" width=\"100px\" height=\"100px\">";
                $count++;
                if ($count % 4 == 0) {
                    $content1 .= '<br>';
                }
                ProductImage::create([
                    'product_id' => $request->product_id,
                    'image_url' => $fileName,
                ]);
            }
            $sanpham = sanpham::find($request->product_id)->name;

            $title = '<strong>Thêm ảnh cho sản phẩm: ' . $sanpham . '</strong> <br>' . '<span style="color: red;">Ảnh:</span>';
            historylog::create([
                'user_id' => Auth::user()->id,
                'role' => Auth::user()->role,
                'content' => $title . $content1,
            ]);
        }
        return redirect()->route('sanpham.edit', ['id' => session('sanpham_id'),])->with('success', 'Thêm sản phẩm và size thành công!');
    }





    public function destroy($id)
    {
        $productImage = ProductImage::findOrFail($id);

        $filePath = 'uploads/' . $productImage->image_url;
        \Log::info('Xóa file: ' . $filePath . ' - Tồn tại: ' . (Storage::disk('public')->exists($filePath) ? 'YES' : 'NO'));
        if ($productImage->image_url && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $sanpham = sanpham::find($productImage->product_id)->name;
        $content1 = '';
        $title = '<strong>Xóa ảnh cho sản phẩm: ' . $sanpham . '</strong> <br>';
        $content1 = " *<span style='color: red;'>Tên ảnh:</span> `$productImage->image_url` <br>";
        $productImage->delete();
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' => $title . $content1,
        ]);
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('sanpham.edit', ['id' => session('sanpham_id')])->with('success', 'Thêm sản phẩm và size thành công!');
    }
}
