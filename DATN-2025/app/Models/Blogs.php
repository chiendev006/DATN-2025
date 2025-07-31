<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Laravel\Prompts\table;

class Blogs extends Model
{
    use HasFactory, SoftDeletes;
    protected  $table = 'blogs';
    protected $fillable = ['title', 'content', 'image', 'blog_id'];
    public function danhmucBlog()
    {
        return $this->belongsTo(DanhmucBlog::class, 'blog_id'); // 'blog_id' là tên cột khóa ngoại trong bảng 'blogs'
    }
}
