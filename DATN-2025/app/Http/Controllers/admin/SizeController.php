<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
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
        $name = $request->name;
        $price = $request->price;
        Size::insert([
            'name' => $name,
            'price' => $price,
        ]);
        return redirect()->route('size.index')->with('success', 'Thêm thành công!');
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
