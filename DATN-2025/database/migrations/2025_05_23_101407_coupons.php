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
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->decimal('discount', 10, 2);
        $table->enum('type', ['percent', 'fixed'])->default('fixed');
        $table->integer('usage_limit')->nullable();
        $table->integer('used')->default(0);
        $table->unsignedBigInteger('user_id')->nullable();
        $table->decimal('min_order_value', 10, 2)->default(0);
        $table->boolean('is_active')->default(true);

        // Thêm cột starts_at
        $table->dateTime('starts_at')->nullable(); // Có thể dùng date() nếu không cần giờ phút giây

        $table->dateTime('expires_at')->nullable(); // Đổi thành dateTime để đồng bộ
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
