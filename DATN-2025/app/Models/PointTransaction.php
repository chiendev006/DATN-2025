<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'description',
        'order_id',
        'created_by'
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeEarned($query)
    {
        return $query->where('type', 'earn');
    }

    public function scopeSpent($query)
    {
        return $query->where('type', 'spend');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function getFormattedPointsAttribute()
    {
        return number_format($this->points);
    }

    public function getTypeTextAttribute()
    {
        $types = [
            'earn' => 'Tích điểm',
            'spend' => 'Sử dụng điểm',
            'expire' => 'Hết hạn',
            'adjust' => 'Điều chỉnh'
        ];

        return $types[$this->type] ?? $this->type;
    }
} 