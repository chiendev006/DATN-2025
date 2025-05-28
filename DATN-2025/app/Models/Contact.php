<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'contact';

    protected $fillable = [
        'name',
        'email',
        'massage', 
    ];

}
