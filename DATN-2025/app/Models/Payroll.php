<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';
    protected $fillable = [
        'month',
        'total',
        'status',
    ];

    // Quan hệ: Một payroll có nhiều payroll_detail
    public function details()
    {
        return $this->hasMany(PayrollDetail::class, 'payroll_id');
    }
}
