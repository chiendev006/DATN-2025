<?php

namespace App\Http\Controllers\admin;

use App\Models\DanhmucBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DanhmucBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $perPage = $request->input('per_page', 5);
       $search = $request->input('search', '');
       
       $query = DanhmucBlog::query();

       // Lọc theo tên
       if (!empty($search)) {
           $query->where('name', 'like', '%' . $search . '%');
       }
       
       $danhmucBlog = $query->paginate($perPage);
       
       return view('admin.danhmucblog.index', [
           'danhmucBlog' => $danhmucBlog,
           'search' => $search,
       ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danhmucblog.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2|unique:danhmuc_blog,name',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục blog',
            'name.string' => 'Tên danh mục blog phải là chuỗi ký tự',
            'name.max' => 'Tên danh mục blog không được quá 255 ký tự',
            'name.min' => 'Tên danh mục blog phải có ít nhất 2 ký tự',
            'name.unique' => 'Tên danh mục blog đã tồn tại',
        ]);

        $name = $request->name;
        DanhmucBlog::insert([
            'name' => $name,
        ]);
        return redirect()->route('danhmucblog.index')->with('success', 'Thêm danh mục blog thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $danhmucBlog = DanhmucBlog::find($id);
        return view('admin.danhmucblog.edit',['danhmucBlog'=>$danhmucBlog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2|unique:danhmuc_blog,name,' . $id,
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục blog',
            'name.string' => 'Tên danh mục blog phải là chuỗi ký tự',
            'name.max' => 'Tên danh mục blog không được quá 255 ký tự',
            'name.min' => 'Tên danh mục blog phải có ít nhất 2 ký tự',
            'name.unique' => 'Tên danh mục blog đã tồn tại',
        ]);

        $name = $request->name;
        DanhmucBlog::where('id', $id)->update([
            'name' => $name,
        ]);
        return redirect()->route('danhmucblog.index')->with('success', 'Sửa danh mục blog thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        DanhmucBlog::destroy($id);
        return redirect()->route('danhmucblog.index')->with('success', 'Xóa danh mục blog thành công!');
    }
}
