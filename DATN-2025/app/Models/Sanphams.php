<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sanphams extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sanphams';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'mota', 'price', 'id_danhmuc', /* các trường khác */];

    public function danhmuc()
    {
        return $this->belongsTo(Danhmucs::class, 'id_danhmuc');
    }
    public function product_img()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }
}
