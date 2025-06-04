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
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->string('name', 255);
            $table->decimal('shipping_fee')->default(0);
            $table->string('address', 255);
            $table->string('phone', 15);
            $table->enum('payment_method', ['cash', 'banking'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');


            $table->decimal('total', 10, 2); // Tổng giá trị đơn hàng (10 chữ số tổng cộng, 2 chữ số sau dấu thập phân)

            // Thay đổi cột transaction_id để tự động sinh UUID (chữ và số ngẫu nhiên)
            // UUID là một chuỗi 36 ký tự (bao gồm dấu gạch ngang) đảm bảo tính duy nhất cao.
            $table->uuid('transaction_id')->nullable(); // ID giao dịch cho thanh toán (nếu có), có thể null

            // Trạng thái thanh toán: 1 (đã thanh toán), 0 (chưa thanh toán) - hoặc các giá trị khác tùy định nghĩa của bạn
            $table->string('pay_status', 10)->default('0');

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

