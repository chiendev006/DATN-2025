<?php

namespace App\Http\Controllers\admin;

use App\Models\sanpham;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\historylog;
use App\Models\Product_topping;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Laravel\Sanctum\Sanctum;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sanpham = Sanpham::with('danhmuc')->paginate($perPage);
        $danhmucs = Danhmuc::all();


        return view('admin.sanpham.index', ['sanpham' => $sanpham, 'danhmucs' => $danhmucs]);
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
    public function store(Request $request)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'name' => 'required|string',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'mota' => 'required|string',
        'id_danhmuc'  => 'required|exists:danhmucs,id',
    ]);

    // Lưu session tạm nếu cần

    // Xử lý ảnh đại diện
    $image = $request->file('image');
    $fileName = uniqid() . $image->getClientOriginalName();
    $image->storeAs('public/uploads/', $fileName);

    // Tạo sản phẩm
    $sanpham = sanpham::create([
        'name' => $request->name,
        'image' => $fileName,
        'title' => $request->title,
        'mota' => $request->mota,
        'id_danhmuc' => $request->id_danhmuc,
    ]);

    // Thêm size nếu có
    if ($request->has('sizes') && is_array($request->sizes)) {
        foreach ($request->sizes as $size) {
            Size::create([
                'product_id' => $sanpham->id,
                'size' => $size['size'],
                'price' => $size['price'],
            ]);
        }
    }

    // Thêm topping nếu có
    if ($request->has('topping_ids') && is_array($request->topping_ids)) {
        foreach ($request->topping_ids as $topping_id) {
            $topping = Topping::find($topping_id);
            if ($topping) {
                Product_topping::create([
                    'product_id' => $sanpham->id,
                    'topping'    => $topping->name,
                    'price'      => $topping->price,
                ]);
            }
        }
    }

    // Thêm nhiều ảnh nếu có
    if ($request->hasFile('hasFile')) {
        foreach ($request->file('hasFile') as $file) {
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads', $fileName);

            ProductImage::create([
                'product_id' => $sanpham->id,
                'image_url' => $fileName,
            ]);
        }
    }
    $sanphamid=sanpham::with(['danhmuc', 'sizes', 'topping'])->find($sanpham->id);
    $content1='';
    $content2='';
    $content3='';
    $content4='';
    $content5='';
    $content6='';


    $title='<strong>Thêm sản phẩm:</strong> <br>';
        $content1=" *<span style='color: red;'>Tên:</span> `$sanphamid->name` <br>";
        $content2= "*<span style='color: red;'>Danh mục:</span> `" . $sanphamid->danhmuc->name . "` <br>";
        $content3= "*<span style='color: red;'>Mô tả:</span> `$sanphamid->mota` <br>";
        $content4= "*<span style='color: red;'>Ảnh bìa:</span> <img src=\"" . url("/storage/uploads/$sanphamid->image") . "\" alt=\"\" width=\"100px\" height=\"100px\"><br>";

$content5 = "<span style='color: red;'>Size:</span><br>";
if (!empty($sanphamid->sizes)) {
    foreach ($sanphamid->sizes as $sizes) {
        $content5 .= ($sizes->size ?? 'N/A') . ' - ' . ($sizes->price ?? 'N/A') . ' VNĐ<br>';
    }
}

if($request->has('topping_ids') && is_array($request->topping_ids)){
$content6 = "<span style='color: red;'>Topping:</span><br>";
if (!empty($sanphamid->topping)) {
    foreach ($sanphamid->topping as $topping) {
        $content6 .= $topping->topping . ' - ' . $topping->price . ' VNĐ<br>';
    }
}
}

    historylog::create([
        'user_id' => Auth::user()->id,
        'role' => Auth::user()->role,
        'content' =>$title.$content1.$content2.$content3.$content5.$content6.$content4,
    ]);
    return redirect()->route('sanpham.edit', ['id' => $sanpham->id])
                     ->with('success', 'Thêm sản phẩm thành công!');
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
        $oldSanpham = clone $sanpham;

        // Validate
        $request->validate([
            'name'=> 'required|string',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mota'=> 'required|string',
            'id_danhmuc'=> 'required|exists:danhmucs,id',
        ]);

        // Xử lý ảnh mới
        if ($request->hasFile('image')) {
            if ($sanpham->image && Storage::exists('public/uploads/' . $sanpham->image)) {
                Storage::delete('public/uploads/' . $sanpham->image);
            }
            $image    = $request->file('image');
            $fileName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/uploads', $fileName);
            $sanpham->image = $fileName;
        }

        // Cập nhật các trường khác
        $sanpham->name = $request->name;
        $sanpham->mota = $request->mota;
        $sanpham->id_danhmuc = $request->id_danhmuc;
        $sanpham->save();

        $content1 = $content2 = $content3 = $content4 = '';

        $title = "<strong>Sửa thông tin sản phẩm: $oldSanpham->name</strong> <br>";
        if($oldSanpham->name != $sanpham->name){
            $content1 = " *<span style='color: red;'>Tên:</span> `$oldSanpham->name`<span style='color: blue;'> thành </span>`$sanpham->name` <br>";
        }
        if($oldSanpham->id_danhmuc != $sanpham->id_danhmuc){
            $oldDanhmuc = $oldSanpham->danhmuc ? $oldSanpham->danhmuc->name : '';
            $newDanhmuc = $sanpham->danhmuc ? $sanpham->danhmuc->name : '';
            $content2 = " *<span style='color: red;'>Danh mục:</span> `$oldDanhmuc` <span style='color: blue;'>thành</span> `$newDanhmuc` <br>";
        }
        if($oldSanpham->mota != $sanpham->mota){
            $content3 = " *<span style='color: red;'>Mô tả:</span> $oldSanpham->mota<span style='color: blue;'> thành </span>$sanpham->mota <br>";
        }
        if($oldSanpham->image != $sanpham->image){
            $content4 = "*<span style='color: red;'>Ảnh bìa:</span> <img src=\"" . url("/storage/uploads/$sanpham->image") . "\" alt=\"\" width=\"100px\" height=\"100px\">";
        }

        if($content1 || $content2 || $content3 || $content4){
            historylog::create([
                'user_id' => Auth::user()->id,
                'role' => Auth::user()->role,
                'content' => $title.$content1.$content2.$content3.$content4,
            ]);
        }

        return redirect()->route('sanpham.edit', ['id' => $sanpham->id])
            ->with('success', 'Cập nhật sản phẩm thành công!');
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

    /**
     * Search sản phẩm theo tên.
     */

public function search(Request $request)
{
    // Bước 1: Validate dữ liệu đầu vào
    $validator = Validator::make($request->all(), [
        'q' => 'nullable|string|max:255',
        'min_price' => 'nullable|numeric|min:0',
        'max_price' => 'nullable|numeric|gte:min_price',
    ], [
        'q.string' => 'Tên sản phẩm phải là chuỗi.',
        'min_price.numeric' => 'Giá từ phải là số.',
        'max_price.numeric' => 'Giá đến phải là số.',
        'max_price.gte' => 'Giá đến phải lớn hơn hoặc bằng giá từ.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Bước 2: Lấy input
    $query = $request->input('q');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    // Bước 3: Truy vấn sản phẩm
    $sanpham = Sanpham::with('danhmuc', 'sizes')
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })
        ->when($minPrice, function ($q) use ($minPrice) {
            $q->whereIn('id', function ($sub) use ($minPrice) {
                $sub->select('product_id')
                    ->from('product_attributes')
                    ->groupBy('product_id')
                    ->havingRaw('MIN(price) >= ?', [$minPrice]);
            });
        })
        ->when($maxPrice, function ($q) use ($maxPrice) {
            $q->whereIn('id', function ($sub) use ($maxPrice) {
                $sub->select('product_id')
                    ->from('product_attributes')
                    ->groupBy('product_id')
                    ->havingRaw('MIN(price) <= ?', [$maxPrice]);
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

    $danhmucs = Danhmuc::all();

    // Bước 4: Trả kết quả
    return view('admin.sanpham.index', ['sanpham' => $sanpham,'search' => $query,'minPrice' => $minPrice,'maxPrice' => $maxPrice,'danhmucs' => $danhmucs
    ]);
}



    /**
     * Lọc sản phẩm theo danh mục.
     */
    public function filterByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        if($categoryId == 'allproduct'){
            return redirect()->route('sanpham.index');
        }else{
            $sanpham = Sanpham::with('danhmuc')
            ->where('id_danhmuc', $categoryId)
            ->paginate(10);
        $search = null;
        $danhmucs = Danhmuc::all();
        return view('admin.sanpham.index', [
            'sanpham' => $sanpham,
            'selectedCategory' => $categoryId,
            'search' => $search,
            'danhmucs' => $danhmucs
        ]);
        }
    }

}
