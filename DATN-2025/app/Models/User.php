<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'role',
        'employee_id',
        'salary_per_day',
        'status',
        'points',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'points' => 'integer',
    ];

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class, 'user_id');
    }

    // Relationships cho điểm tích lũy
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    // Methods liên quan đến điểm tích lũy
    public function addPoints($points, $type = 'earn', $description = null, $orderId = null, $createdBy = null)
    {
        // Tạo transaction record
        $this->pointTransactions()->create([
            'points' => $points,
            'type' => $type,
            'description' => $description,
            'order_id' => $orderId,
            'created_by' => $createdBy
        ]);

        // Cập nhật tổng điểm
        $this->increment('points', $points);
        
        return $this;
    }

    public function usePoints($points, $description = null, $orderId = null, $createdBy = null)
    {
        \Log::info('DEBUG: usePoints called', [
            'user_id' => $this->id,
            'points_to_use' => $points,
            'current_points' => $this->points,
            'description' => $description,
            'order_id' => $orderId
        ]);
        
        if ($this->points < $points) {
            throw new \Exception('Không đủ điểm để sử dụng');
        }

        try {
            // Tạo transaction record (số âm)
            $transaction = $this->pointTransactions()->create([
                'points' => -$points,
                'type' => 'spend',
                'description' => $description,
                'order_id' => $orderId,
                'created_by' => $createdBy
            ]);

            \Log::info('DEBUG: Point transaction created', [
                'transaction_id' => $transaction->id,
                'points' => $transaction->points,
                'type' => $transaction->type
            ]);

            // Cập nhật tổng điểm
            $this->decrement('points', $points);
            
            \Log::info('DEBUG: Points decremented', [
                'user_id' => $this->id,
                'points_decremented' => $points,
                'new_points_total' => $this->fresh()->points
            ]);
            
            return $this;
        } catch (\Exception $e) {
            \Log::error('DEBUG: Error in usePoints', [
                'user_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getFormattedPointsAttribute()
    {
        return number_format($this->points);
    }

    public function canUsePoints($points = null)
    {
        $minPoints = PointSetting::getMinPointsToUse();
        
        if ($points === null) {
            return $this->points >= $minPoints;
        }
        
        return $this->points >= $points && $points >= $minPoints;
    }

    public function getMaxPointsCanUse($orderTotal)
    {
        $maxPercent = PointSetting::getMaxPointsPerOrder();
        $maxPointsByPercent = ($orderTotal * $maxPercent) / 100;
        $maxPointsByVnd = $maxPointsByPercent / PointSetting::getVndPerPoint();
        
        return min($this->points, (int) $maxPointsByVnd);
    }
}
