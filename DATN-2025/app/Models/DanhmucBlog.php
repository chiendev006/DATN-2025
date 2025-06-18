<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use function Laravel\Prompts\table;

class DanhmucBlog extends Model
{
    use HasFactory;
    protected  $table = 'danhmuc_blog';
    protected $fillable = ['id', 'name'];
    public function blogs()
    {
        return $this->hasMany(Blogs::class, 'blog_id'); // 'blog_id' là tên cột khóa ngoại trong bảng 'blogs'
    }
}
