<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\historylog;
use App\Models\Product_topping;
use App\Models\sanpham;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $sanpham = sanpham::find($request->product_id)->name    ;

                $size= Size::create([
                    'product_id' => $request->product_id,
                    'size' => $request->size_name,
                    'price' => $request->size_price,
                ]);


    $content1='';
    $content2='';
    $title='<strong>Thêm size cho sản phẩm: '.$sanpham.'</strong> <br>';
        $content1=" *<span style='color: red;'>Tên size:</span> `$size->size` <br>";
        $content2= "*<span style='color: red;'>Giá:</span> `$size->price` <br>";


    historylog::create([
        'user_id' => Auth::user()->id,
        'role' => Auth::user()->role,
        'content' =>$title.$content1.$content2,
    ]);
             return redirect()->route('sanpham.edit', ['id' => session('sanpham_id'), ])->with('success', 'Thêm sản phẩm và size thành công!');

    }


     public function edit($id) {
        $size = Size::find($id);
        return view('admin.size.edit', compact('size'));
    }

    public function delete($id)
     {
        $size = Size::find($id);
        $sanpham = sanpham::find($size->product_id)->name    ;
        Size::where('id', $id)->delete();
        $content1='';
        $content2='';
        $title='<strong>Xóa size cho sản phẩm: '.$sanpham.'</strong> <br>';
        $content1=" *<span style='color: red;'>Tên size:</span> `$size->size` <br>";
        $content2= "*<span style='color: red;'>Giá:</span> `$size->price` VNĐ <br>";

        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('sanpham.edit')->with('success', 'Xóa thành công!');
    }

    public function deleteTopping($id)
{
    $topping = Product_topping::find($id);
    $sanpham = sanpham::find($topping->product_id)->name    ;
    Product_topping::where('id', $id)->delete();
    $content1='';
    $content2='';
    $title='<strong>Xóa topping cho sản phẩm: '.$sanpham.'</strong> <br>';
        $content1=" *<span style='color: red;'>Tên topping:</span> `$topping->topping` <br>";
        $content2= "*<span style='color: red;'>Giá:</span> `$topping->price` VNĐ <br>";


    historylog::create([
        'user_id' => Auth::user()->id,
        'role' => Auth::user()->role,
        'content' =>$title.$content1.$content2,
    ]);
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
    $sanpham = sanpham::find($request->id)->name    ;


        $topping_ids = $request->input('topping_ids', []);
         foreach ($topping_ids as $topping_id) {
            $topping = Topping::find($topping_id);
            if ($topping) {
                $topping=Product_topping::create([
                    'product_id' => $request->id,
                    'topping'    => $topping->name,
                    'price'      => $topping->price,
                ]);
            }
        }
        $content1='';
        $content2='';
        $title='<strong>Thêm topping cho sản phẩm: '.$sanpham.'</strong> <br>';
            $content1=" *<span style='color: red;'>Tên topping - Giá:</span> <br>";
            foreach ($topping_ids as $topping_id) {
                $topping = Topping::find($topping_id);
                if ($topping) {
                $content2 .= $topping->name. ' - ' . $topping->price . ' VNĐ<br>';
            }
        }
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
        return redirect()->route('sanpham.edit', ['id' => $request->id])->with('success', 'Thêm topping thành công!');

        }





}

