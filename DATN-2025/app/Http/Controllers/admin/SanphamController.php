<?php

namespace App\Http\Controllers\admin;

use App\Models\sanpham;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use Illuminate\Support\Facades\Storage;

use Laravel\Sanctum\Sanctum;

class SanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanpham = Sanpham::with('danhmuc')->paginate(2);
        return view('admin.sanpham.index', ['sanpham' => $sanpham]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $danhmuc = Danhmuc::all();
        return view('admin.sanpham.add', compact('danhmuc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mota' => 'required|string',
            'id_danhmuc'  => 'required|exists:danhmucs,id',
        ]);
        $name = $request->name;
        $price = $request->price;
        $image = $request->file('image');
        $fileName = uniqid() .$image->getClientOriginalName();
        $image->storeAs('public/uploads/',$fileName);
        $mota = $request->mota;
        $id_danhmuc = $request->id_danhmuc;
        sanpham::insert([
            'name' => $name,
            'price' => $price,
            'image' => $fileName,
            'mota' => $mota,
            'id_danhmuc' => $id_danhmuc,
        ]);
        return redirect()->route('sanpham.index')->with('success', 'Thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $sanpham = sanpham::find($id);
        $danhmuc = Danhmuc::all();
        return view('admin.sanpham.edit', compact('danhmuc', 'sanpham'));
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
        'price'=> 'required|numeric',
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
        $sanpham->price = $request->price;
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
    if ($sanpham->image && Storage::exists('public/uploads/' . $sanpham->image)) {
        Storage::delete('public/uploads/' . $sanpham->image);
    }
    $sanpham->delete();
    return redirect()->route('sanpham.index')->with('success', 'Đã xóa sản phẩm!');
    }

}
