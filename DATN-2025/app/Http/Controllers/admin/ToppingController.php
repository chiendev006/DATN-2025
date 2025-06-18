<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToppingRequest;
use App\Models\Topping;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    public function index(Request $request) {
        $perPage = $request->input('per_page', 5);
        $topping = Topping::paginate($perPage);
        return view('admin.topping.index', compact('topping'));
    }
    public function create() {
        return view('admin.topping.add');
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ],
    [
        'name.required' => 'Tên toping không được để trống',
        'name.string' => 'Tên topping phải là một chuỗi kí tự',
        'price.required' => 'Giá topping phải là một chuỗi kí tự',
        'price.numeric' => 'Giá topping phải là dạng số',

    ]);
        $name = $request->name;
        $price = $request->price;
        Topping::insert([
            'name' => $name,
            'price' => $price,
        ]);
        return redirect()->route('topping.index')->with('success', 'Thêm thành công!');
    }

     public function edit($id) {
        $topping = Topping::find($id);
        return view('admin.topping.edit', compact('topping'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $name = $request->name;
        $price = $request->price;
        Topping::where('id', $id)->update([
            'name' => $name,
            'price' => $price,
        ]);
        return redirect()->route('topping.index')->with('success', 'Sửa thành công!');
    }
    public function delete($id) {
        $topping=Topping::find($id);
          \App\Models\Product_topping::where('topping', $topping->name)->delete();
           Topping::destroy($id);
        return redirect()->route('topping.index')->with('success', 'Xóa thành công!');
    }

    public function searchtopping( Request $request){
    $perPage = $request->input('per_page', 5);
            $request->validate([
            'name' => 'required|string',
                   ],
    [
        'name.required' => 'Tên toping không được để trống',
        'name.string' => 'Tên topping phải là một chuỗi kí tự',
    ]);
         $toppings = topping::select('topping.*')
            ->where('name', 'like', "%$request->name%")
            ->paginate($perPage);
        return view('admin.topping.index', ['topping' => $toppings]);
    }
}
