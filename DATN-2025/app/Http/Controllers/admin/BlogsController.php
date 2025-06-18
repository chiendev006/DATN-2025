<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blogs;
use App\Models\DanhmucBlog; // THÊM DÒNG NÀY: Import model DanhmucBlog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blogs::latest()->paginate(5);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        // THÊM DÒNG NÀY: Lấy tất cả các danh mục từ database
        $categories = DanhmucBlog::all();
        // CẬP NHẬT DÒNG NÀY: Truyền biến $categories vào view
        return view('admin.blogs.add', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // CẬP NHẬT DÒNG NÀY: Đổi 'blog-id' thành 'blog_id' và thêm rule 'exists'
            'blog_id' => 'required|integer|exists:danhmuc_blog,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blogs::create([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $imagePath,
            // CẬP NHẬT DÒNG NÀY: Lấy giá trị blog_id từ request
            'blog_id' => $request->blog_id,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Thêm thành công!');
    }

    // Giữ nguyên các phương thức edit, update, destroy của bạn
    public function edit(Request $request, $id)
    {
        $blogs = Blogs::findOrFail($id);
        $categories = DanhmucBlog::all();
        return view('admin.blogs.edit', compact('blogs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // THÊM DÒNG NÀY: Thêm validation cho blog_id
            'blog_id' => 'required|integer|exists:danhmuc_blog,id',
        ]);

        $blog = Blogs::findOrFail($id);
        $blog->title = $request->title;
        $blog->content = $request->content;
        // THÊM DÒNG NÀY: Cập nhật giá trị blog_id
        $blog->blog_id = $request->blog_id;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Cập nhật blog thành công!');
    }

    public function destroy($id)
    {
        $blog = Blogs::findOrFail($id);

        // Xóa ảnh nếu có
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Xóa blog thành công!');
    }
}
