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
       Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name', 255);
            $table->string('phone', 15);
            $table->string('email', 255)->nullable();
            $table->foreignId('address_id')->constrained('address')->onDelete('cascade');
            $table->string('address_detail')->nullable();
            $table->string('district_name')->nullable();
            $table->decimal('shipping_fee')->default(0);
            $table->enum('payment_method', ['cash', 'banking'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->string('cancel_reason', 255)->nullable();
            $table->decimal('total', 10, 2);
            $table->uuid('transaction_id')->nullable();
            $table->enum('pay_status', ['0', '1', '2', '3'])->default('0');
            $table->text('coupon_summary')->nullable();
            $table->decimal('coupon_total_discount', 10, 2)->default(0);
            $table->integer('points_used')->default(0)->comment('Số điểm đã sử dụng cho đơn hàng');
            $table->decimal('points_discount', 15, 2)->default(0)->comment('Số tiền giảm giá từ điểm');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
    }
};

