<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
     protected $fillable = [
        'user_id',
        'total',
        'session_id'
    ];

    public function cartdetails()
    {
        return $this->hasMany(Cartdetail::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
