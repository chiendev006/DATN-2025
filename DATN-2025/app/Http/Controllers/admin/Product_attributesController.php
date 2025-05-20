<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class Product_attributesController extends Controller
{
        public function index() {
        $size = Size::paginate(5);
        return view('admin.size.index', compact('size'));
    }
    public function create() {
        return view('admin.size.add');
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Lấy dữ liệu sản phẩm từ session
        $sanphamData = session('sanpham_data');
        if (!$sanphamData) {
            return redirect()->route('sanpham.create')->with('error', 'Thiếu dữ liệu sản phẩm!');
        }

        // Lưu sản phẩm trước, lấy id
        $sanpham = \App\Models\sanpham::create($sanphamData);

        // Lưu size với id_product là id sản phẩm vừa thêm
        Size::create([
            'product_id' => $sanpham->id,
            'size' => $request->name,
            'price' => $request->price,
        ]);

        // Xóa session
        session()->forget('sanpham_data');

        return redirect()->route('sanpham.index')->with('success', 'Thêm sản phẩm và size thành công!');
    }

     public function edit($id) {
        $size = Size::find($id);
        return view('admin.size.edit', compact('size'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $name = $request->name;
        $price = $request->price;
        Size::where('id', $id)->update([
            'name' => $name,
            'price' => $price,
        ]);
        return redirect()->route('size.index')->with('success', 'Sửa thành công!');
    }
    public function delete($id) {
        Size::destroy($id);
        return redirect()->route('size.index')->with('success', 'Xóa thành công!');
    }
}
