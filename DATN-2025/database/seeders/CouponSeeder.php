<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon; // Đảm bảo import model Coupon
use Carbon\Carbon; // Import Carbon để dễ dàng làm việc với ngày tháng

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả các bản ghi cũ trong bảng coupons (tùy chọn)
        Coupon::truncate();

        // Định nghĩa 4 bản ghi voucher cụ thể
        $couponsToInsert = [
            [
                'code' => 'HESANSALE',
                'discount' => 20,
                'type' => 'percent',
                'usage_limit' => 100,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 200000,
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => Carbon::create(2025, 8, 19)

            ],
            [
                'code' => 'MIRAKHAICHUONG',
                'discount' => 30000,
                'type' => 'fixed',
                'usage_limit' => 100,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 150000,
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => Carbon::create(2025, 6, 21),
            ],
             [
                'code' => 'MIRASALE',
                'discount' => 10000,
                'type' => 'fixed',
                'usage_limit' => 100,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 30000,
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => null,

            ],

        ];

        // Chèn tất cả 4 bản ghi vào database chỉ với một truy vấn duy nhất
        Coupon::insert($couponsToInsert);
    }
}
