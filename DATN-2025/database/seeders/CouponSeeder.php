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
                'code' => 'FIXED100K',
                'discount' => 100000.00,
                'type' => 'fixed',
                'usage_limit' => null,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 300000.00,
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => null, // Không có ngày hết hạn
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'PERCENT10',
                'discount' => 0.10, // 10%
                'type' => 'percent',
                'usage_limit' => null,
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 0.00, // Áp dụng cho mọi giá trị đơn hàng
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => Carbon::now()->addMonths(3), // Hết hạn sau 3 tháng
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'NEWUSER20K',
                'discount' => 20000.00,
                'type' => 'fixed',
                'usage_limit' => 100, // Giới hạn 100 lượt sử dụng
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 150000.00,
                'is_active' => true,
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => Carbon::now()->addYear(), // Hết hạn sau 1 năm
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'FREESHIP50',
                'discount' => 0.50, // 50% cho phí ship (ví dụ)
                'type' => 'percent',
                'usage_limit' => 50, // Giới hạn 50 lượt sử dụng
                'used' => 0,
                'user_id' => null,
                'min_order_value' => 50000.00,
                'is_active' => false, // Voucher này không hoạt động ngay lập tức
                'starts_at' => Carbon::create(2025, 6, 19),
                'expires_at' => Carbon::create(2025, 12, 31), // Hết hạn vào cuối năm 2025
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Chèn tất cả 4 bản ghi vào database chỉ với một truy vấn duy nhất
        Coupon::insert($couponsToInsert);
    }
}
