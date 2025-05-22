<?php

namespace App\Http\Controllers\admin;

use App\Models\Danhmuc;
use Illuminate\Http\Request;
use Illuminate\Routing\ControllerDispatcher;
use Illuminate\View\ViewServiceProvider;
use App\Http\Controllers\Controller;


class DanhmucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $danhmuc = Danhmuc::paginate(5);
       return view('admin.danhmuc.index',['danhmuc'=>$danhmuc]);
    }

    /**e
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danhmuc.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'has_topping' => 'required|boolean',
        ]);
        $name = $request->name;
        Danhmuc::insert([
            'name' => $name,
            'role'=>$request->has_topping
        ]);
        return redirect()->route('danhmuc.index')->with('success', 'Thêm thành công!');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $danhmuc = Danhmuc::find($id);
        $danhmuc->has_topping = $danhmuc->role;
        return view('admin.danhmuc.edit',['danhmuc'=>$danhmuc]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $name = $request->name;
        Danhmuc::where('id', $id)->update([
            'name' => $name,
            "role"=>$request->has_topping
        ]);
        return redirect()->route('danhmuc.index')->with('success', 'Sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        Danhmuc::destroy($id);
        return redirect()->route('danhmuc.index')->with('success', 'Xóa thành công!');
    }
}
