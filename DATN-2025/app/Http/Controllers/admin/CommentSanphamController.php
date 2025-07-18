<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CommentSanpham;
use App\Models\sanpham;
use Illuminate\Http\Request;

class CommentSanphamController extends Controller
{
    public function index(Request $request)
    {
        $comments = CommentSanpham::with('sanpham')->orderBy('id', 'desc')->paginate(10);
        return view('admin.CommentSanpham.index', compact('comments'));
    }

    public function delete($id)
    {
        $comment = CommentSanpham::findOrFail($id);
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Đã xóa đánh giá!');
    }
}
