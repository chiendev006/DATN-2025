<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\table;

class Blogs extends Model
{
    use HasFactory;
    protected  $table = 'blogs';
    protected $fillable = ['title', 'content', 'image'];
}
