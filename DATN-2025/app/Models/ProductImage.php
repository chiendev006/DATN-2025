<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images'; 

    protected $fillable = [
        'product_id',
        'attribute_id',
        'image_url',
        'size_id',
        'topping_id',
        'is_primary',
    ];
    public function size()
{
    return $this->belongsTo(Size::class);
}
public function sanpham()
{
    return $this->belongsTo(sanpham::class, 'product_id');
}


public function topping()
{
    return $this->belongsTo(Topping::class);
}


}
