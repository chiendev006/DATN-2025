<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use function Laravel\Prompts\table;

class Blogs extends Model
{
    use HasFactory;
    protected  $table = 'blogs';
    protected $fillable = ['title', 'content', 'image', 'blog_id'];
    public function danhmucBlog()
    {
        return $this->belongsTo(DanhmucBlog::class, 'blog_id'); // 'blog_id' là tên cột khóa ngoại trong bảng 'blogs'
    }
}
