<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sanpham extends Model
{
    use HasFactory;
    use SoftDeletes;
     protected $table = 'sanphams';
    public function danhmuc()
{
    return $this->belongsTo(Danhmuc::class, 'id_danhmuc');
}

public function attributes()
{
    return $this->hasMany(\App\Models\Product_attrlbutes::class, 'product_id');
}

public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
