<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\DanhmucBlog; // Import model DanhmucBlog
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str cho Str::limit trong Blade nếu chưa có

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     * Also fetches all categories to display in the sidebar.
     */
    public function index()
    {
        $blogs = Blogs::paginate(10); // Lấy danh sách bài viết
        $categories = DanhmucBlog::all(); // Lấy tất cả danh mục để hiển thị ở sidebar

        return view('client.blog', compact('blogs', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($id)
    {
        $blog = Blogs::find($id);

        if (!$blog) {
            abort(404); // Tránh lỗi nếu không tìm thấy bài viết
        }

        $categories = DanhmucBlog::all(); // Lấy tất cả danh mục cho sidebar trên trang chi tiết
        return view('client.blog-single', compact('blog', 'categories'));
    }

    /**
     * Search for blog posts based on a keyword.
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        // Tìm bài viết theo từ khóa
        $blogs = Blogs::where('title', 'like', "%$keyword%")
            ->orWhere('content', 'like', "%$keyword%")
            ->paginate(10);

        $categories = DanhmucBlog::all(); // Lấy tất cả danh mục cho sidebar
        return view('client.blog', compact('blogs', 'keyword', 'categories'));
    }

    /**
     * Display blog posts by a specific category.
     *
     * @param  int  $id  ID của danh mục
     * @return \Illuminate\View\View
     */
    public function showByCategory($id)
    {
        // Tìm danh mục theo ID
        $danhmucBlog = DanhmucBlog::find($id);

        if (!$danhmucBlog) {
            abort(404); // Tránh lỗi nếu không tìm thấy danh mục
        }

        // Lấy các bài blog thuộc danh mục đã chọn và phân trang
        // Sử dụng mối quan hệ blogs() đã định nghĩa trong DanhmucBlog model
        $blogs = $danhmucBlog->blogs()->paginate(10);

        $categories = DanhmucBlog::all(); // Lấy tất cả danh mục để hiển thị ở sidebar
        $currentCategory = $danhmucBlog; // Để biết đang ở danh mục nào và có thể highlight

        return view('client.blog', compact('blogs', 'categories', 'currentCategory'));
    }
}
