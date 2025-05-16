<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danhmuc extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function danhmuc()
{
    return $this->belongsTo(Danhmuc::class, 'id_danhmuc');
}
public function sanphams()
{
    return $this->hasMany(Sanpham::class, 'id_danhmuc');
}

}
