<?php

namespace App\Http\Controllers\admin;

use App\Models\sanpham;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\Product_topping;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Support\Facades\Storage;

use Laravel\Sanctum\Sanctum;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanpham = Sanpham::with('danhmuc')->paginate(10);
        return view('admin.sanpham.index', ['sanpham' => $sanpham]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $danhmuc = Danhmuc::all();
        $topping = Topping::all();
        return view('admin.sanpham.add', compact('danhmuc','topping'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mota' => 'required|string',
            'id_danhmuc'  => 'required|exists:danhmucs,id',
        ]);
        // Lưu file ảnh tạm thời
        $image = $request->file('image');
        $fileName = uniqid() . $image->getClientOriginalName();
        $image->storeAs('public/uploads/', $fileName);

        // Lưu dữ liệu vào session hoặc truyền qua route
        $data = [
            'name' => $request->name,
            'image' => $fileName,
            'mota' => $request->mota,
            'id_danhmuc' => $request->id_danhmuc,
        ];
        // Có thể dùng session hoặc truyền qua route, ví dụ dùng session:
        session(['sanpham_data' => $data]);
        session(['product_topping_ids' => $request->input('topping_ids', [])]);

        return redirect()->route('size.add');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $sanpham = sanpham::find($id);
        $danhmuc = Danhmuc::all();
        session(['sanpham_id' => $sanpham->id]);
        $product_img = ProductImage::where('product_id', $id)->get();
        $size = Size::where('product_id', $id)->get();
        $topping_list = Topping::all();
        // Mặc định $topping_detail là mảng rỗng
        $topping_detail = [];

        // Kiểm tra role của danh mục
        $danhmuc_sp = Danhmuc::find($sanpham['id_danhmuc']);
        if ($danhmuc_sp && $danhmuc_sp->role == 1) {
            $role=1;
            $topping_detail = Product_topping::where('product_id', $sanpham['id'])->get();
        }else{
            $role=0;
        }


        return view('admin.sanpham.edit', compact('danhmuc', 'sanpham', 'size', 'product_img', 'topping_detail','topping_list','role'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id){
        $sanpham = sanpham::findOrFail($id);
        $request->validate([
        'name'=> 'required|string',
        'image'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'mota'=> 'required|string',
        'id_danhmuc'=> 'required|exists:danhmucs,id',
        ]);
        if ($request->hasFile('image')) {
        if ($sanpham->image && Storage::exists('public/uploads/' . $sanpham->image)) {
            Storage::delete('public/uploads/' . $sanpham->image);
        }
        $image    = $request->file('image');
        $fileName = uniqid() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/uploads', $fileName);
        $sanpham->image = $fileName;
        }
        $sanpham->name = $request->name;
        $sanpham->mota = $request->mota;
        $sanpham->id_danhmuc = $request->id_danhmuc;
        $sanpham->save();
        return redirect()->route('sanpham.index')->with('success', 'Cập nhật thành công!');
        }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id){
    $sanpham = sanpham::findOrFail($id);

    // Xóa ảnh đại diện sản phẩm
    if ($sanpham->image && Storage::exists('public/uploads/' . $sanpham->image)) {
        Storage::delete('public/uploads/' . $sanpham->image);
    }

    // Xóa tất cả size (product_attribute/Size)
    \App\Models\Size::where('product_id', $sanpham->id)->delete();

    // Xóa tất cả topping liên quan
    \App\Models\Product_topping::where('product_id', $sanpham->id)->delete();

    // Xóa tất cả ảnh gallery liên quan
    $productImages = \App\Models\ProductImage::where('product_id', $sanpham->id)->get();
    foreach ($productImages as $img) {
        if ($img->image_url && Storage::exists('public/uploads/' . $img->image_url)) {
            Storage::delete('public/uploads/' . $img->image_url);
        }
        $img->delete();
    }

    // Xóa sản phẩm
    $sanpham->delete();

    return redirect()->route('sanpham.index')->with('success', 'Đã xóa sản phẩm!');
}
}
