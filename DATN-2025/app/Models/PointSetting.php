<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description'
    ];

    // Helper methods
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description
            ]
        );
    }

    // Các method tiện ích cho cấu hình điểm
    public static function getPointsPerVnd()
    {
        return (int) self::getValue('points_per_vnd', 10000);
    }

    public static function getVndPerPoint()
    {
        return (int) self::getValue('vnd_per_point', 1000);
    }

    public static function getMinPointsToUse()
    {
        return (int) self::getValue('min_points_to_use', 10);
    }

    public static function getMaxPointsPerOrder()
    {
        return (int) self::getValue('max_points_per_order', 50);
    }

    public static function getPointsExpireMonths()
    {
        return (int) self::getValue('points_expire_months', 12);
    }

    public static function isPointsSystemEnabled()
    {
        return (bool) self::getValue('enable_points_system', 1);
    }
} 