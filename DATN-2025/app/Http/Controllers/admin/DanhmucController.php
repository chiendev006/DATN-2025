<?php

namespace App\Http\Controllers\admin;

use App\Models\Danhmuc;
use Illuminate\Http\Request;
use Illuminate\Routing\ControllerDispatcher;
use Illuminate\View\ViewServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\historylog;
use Illuminate\Support\Facades\Auth;

class DanhmucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search', '');
        $filterType = $request->input('filter_type', '');

        $query = Danhmuc::query();

        // Lọc theo tên
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Lọc theo loại danh mục
        if ($filterType !== '') {
            $query->where('role', $filterType);
        }

        $danhmuc = $query->paginate($perPage);

        return view('admin.danhmuc.index', [
            'danhmuc' => $danhmuc,
            'search' => $search,
            'filterType' => $filterType
        ]);
    }

    /**
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
            'name' => 'required|string|max:255|min:2|unique:danhmucs,name',
            'has_topping' => 'required|in:0,1',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự',
            'name.max' => 'Tên danh mục không được quá 255 ký tự',
            'name.min' => 'Tên danh mục phải có ít nhất 2 ký tự',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'has_topping.required' => 'Vui lòng chọn loại danh mục',
            'has_topping.in' => 'Loại danh mục không hợp lệ'
        ]);

        $name = $request->name;
        Danhmuc::insert([
            'name' => $name,
            'role' => $request->has_topping
        ]);
        $content1='';

        $title='<strong>Thêm danh mục sản phẩm:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> `$name` <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1,
        ]);
        return redirect()->route('danhmuc.index')->with('success', 'Thêm thành công!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2|unique:danhmucs,name,' . $id,
            'has_topping' => 'required|in:0,1',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự',
            'name.max' => 'Tên danh mục không được quá 255 ký tự',
            'name.min' => 'Tên danh mục phải có ít nhất 2 ký tự',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'has_topping.required' => 'Vui lòng chọn loại danh mục',
            'has_topping.in' => 'Loại danh mục không hợp lệ'
        ]);
        $nameid=Danhmuc::find($id);
        $danhmuc=Danhmuc::find($id);
       $danhmuc->name=$request->name;
       $danhmuc->role=$request->has_topping;
        $danhmuc->save();
        $danhmuc=Danhmuc::find($id);
        $content1='';
        $content2='';


        $title="<strong>Sửa danh mục sản phẩm: $danhmuc->name</strong> <br>";
        if($nameid->name!=$danhmuc->name){
            $content1=" *<span style='color: red;'>Tên:</span> ` $nameid->name`<span style='color: blue;'> thành </span>`$danhmuc->name` <br>";
        }
        if($nameid->role!=$danhmuc->role){

            $role_userid = ($nameid->role == 0) ? "Không dùng toppping" : "Có dùng topping";
            $role_user = ($danhmuc->role == 0) ?  "Không dùng toppping" : "Có dùng topping";

            $content2 = " *<span style='color: red;'>Loại:</span>`" . $role_userid . "` <span style='color: blue;'>thành</span> `" . $role_user . "` <br>";
        }

      if($content1!=''||$content2!=''){
        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1.$content2,
        ]);
      }
        return redirect()->route('danhmuc.index')->with('success', 'Sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $name=Danhmuc::find($id)->name;
        $content1='';

        $title='<strong>Xóa danh mục:</strong> <br>';
            $content1=" *<span style='color: red;'>Tên:</span> `$name` <br>";


        historylog::create([
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->role,
            'content' =>$title.$content1,
        ]);
        Danhmuc::destroy($id);
        return redirect()->route('danhmuc.index')->with('success', 'Xóa thành công!');
    }
}
