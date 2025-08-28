<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_topping extends Model
{
    use HasFactory;
    use SoftDeletes;
     protected $fillable = [
        'product_id',
        'topping',
        'price',
    ];
    protected $table = 'product_topping';
}
