<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attrlbutes extends Model
{
    use HasFactory;
     protected $table = 'product_attributes'; 
     public function product()
    {
        return $this->belongsTo(sanpham::class, 'product_id');
    }
}
