<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str; // For UUIDs/transaction_id

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu muốn chạy lại seeder nhiều lần mà không bị trùng
        // DB::table('orders')->truncate();

        $paymentMethods = ['cash', 'banking'];
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        // Lấy danh sách user IDs có sẵn (hoặc tạo giả định nếu chưa có UserSeeder)
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) {
            // Fallback: nếu chưa có user nào, tạo tạm 5 user
            for ($i = 1; $i <= 5; $i++) {
                DB::table('users')->insert([
                    'name' => 'Test User ' . $i,
                    'email' => 'test' . $i . '@example.com',
                    'password' => bcrypt('password'), // Chỉ dùng cho mục đích seeding
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            $userIds = DB::table('users')->pluck('id')->toArray();
        }

        // Danh sách tên đường/phố và tên quận/huyện để tạo địa chỉ ngẫu nhiên
        $streetNames = ['Lê Lợi', 'Nguyễn Huệ', 'Trần Hưng Đạo', 'Phạm Ngũ Lão', 'Võ Văn Tần', 'Cách Mạng Tháng Tám', 'Đồng Khởi', 'Nam Kỳ Khởi Nghĩa', 'Hai Bà Trưng', 'Láng Hạ', 'Xuân Thủy', 'Cầu Giấy'];
        $districtNames = [
            'Quận 1', 'Quận 3', 'Quận 5', 'Quận 7', 'Quận Tân Bình', 'Quận Bình Thạnh', // TP.HCM
            'Quận Ba Đình', 'Quận Đống Đa', 'Quận Cầu Giấy', 'Quận Hoàng Mai', 'Quận Thanh Xuân', // Hà Nội
            'Huyện Bình Chánh', 'Huyện Hóc Môn', 'Huyện Từ Liêm', // Các huyện
            'Thành phố Thủ Đức', 'Thành phố Đà Nẵng', // Các thành phố
        ];


        for ($i = 1; $i <= 100; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $orderDate = Carbon::now()->subDays(rand(1, 60))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $shippingFee = rand(10000, 30000); // VNĐ, để dạng int/decimal trực tiếp
            $totalAmount = rand(100000, 2000000); // VNĐ, để dạng int/decimal trực tiếp
            $couponDiscount = ($totalAmount > 500000 && rand(0,1)) ? rand(10000, 50000) : 0;
            $transactionId = ($paymentMethod == 'banking') ? Str::uuid() : null;
            $payStatus = ($status == 'completed' || $status == 'shipped') ? 1 : 0;
            $cancelReason = ($status == 'cancelled') ? 'Customer requested cancellation.' : null;

            // Tạo address_detail ngẫu nhiên
            $addressDetail = 'Số ' . rand(1, 200) . ' ' . $streetNames[array_rand($streetNames)] . ', Phường ' . rand(1, 15);


            DB::table('orders')->insert([
                'user_id' => $userId,
                'name' => 'Khách hàng ' . $userId,
                'phone' => '0' . rand(100000000, 999999999),
                'email' => 'customer' . $userId . '_' . rand(1, 100) . '@example.com', // Thêm rand để email độc nhất
                'address_id' => rand(1, 5), // Lấy address_id ngẫu nhiên từ 1 đến 5
                'address_detail' => $addressDetail, // Địa chỉ chi tiết ngẫu nhiên
                'district_name' => $districtNames[array_rand($districtNames)], // Tên quận/huyện ngẫu nhiên
                'shipping_fee' => $shippingFee,
                'payment_method' => $paymentMethod,
                'status' => $status,
                'cancel_reason' => $cancelReason,
                'total' => $totalAmount,
                'transaction_id' => $transactionId,
                'pay_status' => $payStatus,
                'coupon_summary' => ($couponDiscount > 0) ? 'Giảm giá ' . number_format($couponDiscount) . ' VND' : null,
                'coupon_total_discount' => $couponDiscount,
                'note' => (rand(0, 1) ? 'Giao hàng sau 17h, gọi trước khi đến.' : null),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
        }
    }
}
