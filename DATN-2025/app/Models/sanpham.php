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
     protected $fillable = [
    'name',
    'image',
    'title',
    'mota',
    'id_danhmuc',
];
    public function danhmuc()
{
    return $this->belongsTo(Danhmuc::class, 'id_danhmuc');
}

public function attributes()
{
    return $this->hasMany(\App\Models\Size::class, 'product_id');
}

public function topping()
{
    return $this->hasMany(\App\Models\Product_topping::class, 'product_id');
}


public function product_images()
{
    return $this->hasMany(ProductImage::class, 'product_id');
}
public function sizes()
{
    return $this->hasMany(Size::class, 'product_id')->select(['id', 'product_id', 'price']);
}
    public function comments()
    {
        return $this->hasMany(Product_comment::class, 'product_id');
    }
}

