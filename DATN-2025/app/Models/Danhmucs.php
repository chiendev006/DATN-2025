<?php

namespace App\Models;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danhmucs extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'danhmucs'; // Đặt tên bảng nếu khác 'danhmucs'
    protected $primaryKey = 'id'; // Đặt khóa chính nếu khác 'id'
    protected $fillable = ['name'];

  public function sanphams()
{
    return $this->hasMany(Sanphams::class, 'id_danhmuc');
}
}
