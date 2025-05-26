<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product_topping;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Http\Request;

class Product_attributesController extends Controller
{
        public function index() {
        $size = Size::paginate(5);
        return view('admin.size.index', compact('size'));
    }
    public function store(Request $request) {
        $request->validate([
            'sizes.*.name' => 'required|string',
            'sizes.*.price' => 'required|numeric',
        ]);

                Size::create([
                    'product_id' => $request->product_id,
                    'size' => $request->size_name,
                    'price' => $request->size_price,
                ]);
             return redirect()->route('sanpham.edit', ['id' => session('sanpham_id'), ])->with('success', 'Thêm sản phẩm và size thành công!');

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

    public function deleteTopping($id)
{
   Product_topping::where('id', $id)->delete();
    if (request()->ajax()) {
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
}





        public function addToppingDetail(Request $request){
        $request->validate([
        'topping_ids'   => 'required|array',
        'topping_ids.*' => 'exists:topping,id',
    ]);

        $topping_ids = $request->input('topping_ids', []);
         foreach ($topping_ids as $topping_id) {
            $topping = Topping::find($topping_id);
            if ($topping) {
                Product_topping::create([
                    'product_id' => $request->id,
                    'topping'    => $topping->name,
                    'price'      => $topping->price,
                ]);
            }
        }
        return redirect()->route('sanpham.edit', ['id' => $request->id])->with('success', 'Thêm topping thành công!');

        }





}

