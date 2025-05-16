<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_attrlbutes extends Model
{
    use HasFactory;
     protected $table = 'product_attributes';
     public function size()
{
    return $this->belongsTo(\App\Models\Size::class, 'size_id');
}

public function topping()
{
    return $this->belongsTo(\App\Models\Topping::class, 'topping_id');
}

}
