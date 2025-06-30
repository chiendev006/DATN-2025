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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('points'); // Số điểm (dương = cộng, âm = trừ)
            $table->enum('type', ['earn', 'spend', 'expire', 'adjust']); // Loại giao dịch
            $table->string('description')->nullable(); // Mô tả giao dịch
            $table->unsignedBigInteger('order_id')->nullable(); // ID đơn hàng liên quan
            $table->unsignedBigInteger('created_by')->nullable(); // Người tạo (admin/staff)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
}; 