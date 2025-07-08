<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historylog extends Model
{
    use HasFactory;
    protected  $table = 'historylogs';
    protected $fillable = ['id', 'name','role','content'];
    use HasFactory;
}
