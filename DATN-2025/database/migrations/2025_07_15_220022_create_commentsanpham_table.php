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
        Schema::create('commentsanpham', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Khóa ngoại đến bảng sản phẩm
            $table->text('comment');                 // Nội dung bình luận
            $table->string('user_name')->nullable(); // Tên người dùng (nếu có)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentsanpham');
    }
};
