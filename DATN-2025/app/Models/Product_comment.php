<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_comment extends Model
{
    use HasFactory;
    protected $table = 'product_comment';

    protected $fillable = [
        'product_id',
        'user_id',
        'comment',
        'rating',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
