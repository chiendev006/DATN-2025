<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $table = 'payroll_details';
    protected $fillable = [
        'payroll_id',
        'user_id',
        'total_days',
        'total_salary',
        'work_days',
    ];

    // Quan hệ: payroll_detail thuộc về payroll
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    // Quan hệ: payroll_detail thuộc về user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Lấy mảng ngày công (accessor)
    public function getWorkDaysArrayAttribute()
    {
        return json_decode($this->work_days, true) ?? [];
    }
}
