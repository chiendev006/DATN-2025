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
            'sizes.*.name' => 'required|string',
            'sizes.*.price' => 'required|numeric',
        ]);

        if (session('sanpham_data')) {
            // Từ create sản phẩm
            $sanphamData = session('sanpham_data');
            $sanpham = \App\Models\sanpham::create($sanphamData);

            foreach ($request->sizes as $size) {
                Size::create([
                    'product_id' => $sanpham->id,
                    'size' => $size['name'],
                    'price' => $size['price'],
                ]);
            }
            session()->forget('sanpham_data');
             return redirect()->route('sanpham.index')->with('success', 'Thêm sản phẩm và size thành công!');
        } else {
            // Từ edit sản phẩm
                foreach ($request->sizes as $size) {
                Size::create([
                    'product_id' => session('sanpham_id'),
                    'size' => $size['name'],
                    'price' => $size['price'],
                ]);
                return redirect()->route('sanpham.edit', ['id' => session('sanpham_id'), ])->with('success', 'Thêm sản phẩm và size thành công!');

            }

        }
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
        \App\Models\Size::destroy($id);
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('sanpham.edit')->with('success', 'Xóa thành công!');
    }

}
