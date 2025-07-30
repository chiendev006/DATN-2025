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
            'id_danhmuc' => 'required|exists:danhmucs,id', // Đảm bảo bảng 'danhmucs' và cột 'id' tồn tại
        ]);

        // Xử lý ảnh đại diện
        $image = $request->file('image');
        $fileName = uniqid() . $image->getClientOriginalName();
        $image->storeAs('public/uploads/', $fileName); // Lưu ảnh vào storage/app/public/uploads

        // Tạo sản phẩm mới
        $sanpham = sanpham::create([
            'name' => $request->name,
            'image' => $fileName,
            'title' => $request->title, // Đảm bảo 'title' có trong fillable của model SanPham
            'mota' => $request->mota,
            'id_danhmuc' => $request->id_danhmuc,
        ]);

        // Thêm size nếu có
        if ($request->has('sizes') && is_array($request->sizes)) {
            foreach ($request->sizes as $size) {
                // Đảm bảo các khóa 'size' và 'price' tồn tại trong mỗi phần tử của mảng 'sizes'
                Size::create([
                    'product_id' => $sanpham->id,
                    'size' => $size['size'] ?? null, // Sử dụng null coalescing operator để an toàn
                    'price' => $size['price'] ?? null,
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
                        'topping' => $topping->name,
                        'price' => $topping->price,
                    ]);
                }
            }
        }

        // Thêm nhiều ảnh nếu có
        if ($request->hasFile('hasFile')) { // Đổi tên input nếu bạn muốn từ 'hasFile' sang tên rõ ràng hơn, ví dụ 'product_images[]'
            foreach ($request->file('hasFile') as $file) {
                $fileName = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploads', $fileName);

                ProductImage::create([
                    'product_id' => $sanpham->id,
                    'image_url' => $fileName,
                ]);
            }
        }

        // Lấy lại sản phẩm với các mối quan hệ để ghi log
        $sanphamid = sanpham::with(['danhmuc', 'sizes', 'topping'])->find($sanpham->id);

        $content1 = '';
        $content2 = '';
        $content4 = '';
        $content5 = '';
        $content6 = '';

        $title = '<strong>Thêm sản phẩm:</strong> <br>';
        $content1 = "<span style='color: red;'>*Tên:</span> `" . ($sanphamid->name ?? 'N/A') . "` ";
        $content2 = "*<span style='color: red;'>Danh mục:</span> `" . ($sanphamid->danhmuc->name ?? 'N/A') . "` <br>";
        $content4 = "*<span style='color: red;'>Ảnh bìa:</span> <img src=\"" . url("/storage/uploads/" . ($sanphamid->image ?? '')) . "\" alt=\"\" width=\"100px\" height=\"100px\"><br>";

        // Xây dựng nội dung cho Size
        $content5 = "<div style='flex: 1; padding-right: 10px;'><span style='color: red;'>Size:</span><br>";
        if (!empty($sanphamid->sizes)) {
            foreach ($sanphamid->sizes as $size) {
                $content5 .= ($size->size ?? 'N/A') . ' - ' . ($size->price ?? 'N/A') . ' VNĐ<br>';
            }
        }
        $content5 .= '</div>';

        // Xây dựng nội dung cho Topping
        $content6 = ''; // Khởi tạo rỗng
        if (!empty($sanphamid->topping)) { // Kiểm tra sau khi đã load mối quan hệ
            $content6 = "<div style='flex: 1;'><span style='color: red;'>Topping:</span><br>";
            foreach ($sanphamid->topping as $topping) {
                $content6 .= ($topping->topping ?? 'N/A') . ' - ' . ($topping->price ?? 'N/A') . ' VNĐ<br>';
            }
            $content6 .= '</div>';
        }

        // Ghi log lịch sử hành động
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' => '<div style="max-width: 500px;">' .
                         $title . $content1 . $content2 .
                         '<div style="display:flex; justify-content: space-between;">' . $content6 . $content5 . '</div>' .
                         $content4 .
                         '</div>',
        ]);

        // Chuyển hướng về trang chỉnh sửa sản phẩm với thông báo thành công
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

    // KHÔNG xóa ảnh để giữ lại cho đơn hàng
    // if ($sanpham->image && Storage::exists('public/uploads/' . $sanpham->image)) {
    //     Storage::delete('public/uploads/' . $sanpham->image);
    // }

    // KHÔNG xóa size và topping để giữ lại thông tin cho đơn hàng
    // \App\Models\Size::where('product_id', $sanpham->id)->delete();
    // \App\Models\Product_topping::where('product_id', $sanpham->id)->delete();

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
