<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blogs::all();
        return view('client.blog', compact('blog'));
    }
    public function show($id)
    {
        $blog = Blogs::find($id);
        return view('client.blog-single', compact('blog'));
    }
}
