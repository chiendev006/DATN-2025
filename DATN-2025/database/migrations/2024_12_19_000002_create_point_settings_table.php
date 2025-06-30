<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('point_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Tên cấu hình
            $table->text('value'); // Giá trị cấu hình
            $table->string('description')->nullable(); // Mô tả
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định
        DB::table('point_settings')->insert([
            [
                'key' => 'points_per_vnd',
                'value' => '10000', // 10.000đ = 1 điểm
                'description' => 'Số tiền (VND) để tích được 1 điểm'
            ],
            [
                'key' => 'vnd_per_point',
                'value' => '1000', // 1 điểm = 1.000đ
                'description' => 'Giá trị 1 điểm (VND) khi sử dụng'
            ],
            [
                'key' => 'min_points_to_use',
                'value' => '10', // Tối thiểu 10 điểm mới được sử dụng
                'description' => 'Số điểm tối thiểu để có thể sử dụng'
            ],
            [
                'key' => 'max_points_per_order',
                'value' => '50', // Tối đa 50% giá trị đơn hàng
                'description' => 'Phần trăm tối đa điểm có thể dùng cho 1 đơn hàng (%)'
            ],
            [
                'key' => 'points_expire_months',
                'value' => '12', // Điểm hết hạn sau 12 tháng
                'description' => 'Số tháng điểm có hiệu lực trước khi hết hạn'
            ],
            [
                'key' => 'enable_points_system',
                'value' => '1', // Bật hệ thống điểm
                'description' => 'Bật/tắt hệ thống tích điểm (1=on, 0=off)'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_settings');
    }
}; 