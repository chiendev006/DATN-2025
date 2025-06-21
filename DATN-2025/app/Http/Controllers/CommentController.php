<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product_comment;
use App\Models\Sanpham;

class CommentController extends Controller
{
    public function index($id)
    {
        $sanpham = Sanpham::findOrFail($id);

        $comments = Product_comment::with('user')
            ->where('product_id', $id) 
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.comment', compact('comments', 'sanpham'));
    }
}
