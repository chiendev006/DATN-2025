<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id(); // Khóa chính tự tăng
            $table->foreignId('user_id') // Khóa ngoại liên kết với bảng 'users'
                  ->nullable() // Cho phép giá trị null (nếu người dùng không đăng nhập hoặc đã bị xóa)
                  ->constrained('users') // Ràng buộc khóa ngoại với bảng 'users'
                  ->onDelete('set null'); // Khi user bị xóa, user_id trong order sẽ thành null

            $table->string('name', 255); // Tên người đặt hàng
            $table->string('address', 255); // Địa chỉ giao hàng
            $table->string('phone', 15); // Số điện thoại liên hệ

            // Phương thức thanh toán: 'cash' (tiền mặt), 'banking' (chuyển khoản)
            $table->enum('payment_method', ['cash', 'banking'])->default('cash');

            // Trạng thái đơn hàng: 'pending' (chờ xử lý), 'processing' (đang xử lý), 'completed' (hoàn thành), 'cancelled' (đã hủy)
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

            $table->decimal('total', 10, 2); // Tổng giá trị đơn hàng (10 chữ số tổng cộng, 2 chữ số sau dấu thập phân)

            // Thay đổi cột transaction_id để tự động sinh UUID (chữ và số ngẫu nhiên)
            // UUID là một chuỗi 36 ký tự (bao gồm dấu gạch ngang) đảm bảo tính duy nhất cao.
            $table->uuid('transaction_id')->nullable(); // ID giao dịch cho thanh toán (nếu có), có thể null

            // Trạng thái thanh toán: 1 (đã thanh toán), 0 (chưa thanh toán) - hoặc các giá trị khác tùy định nghĩa của bạn
            $table->string('pay_status', 10)->default('1');

            $table->timestamps(); // Tạo cột 'created_at' và 'updated_at' tự động
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders'); // Xóa bảng 'orders' nếu tồn tại khi rollback migration
    }
};

