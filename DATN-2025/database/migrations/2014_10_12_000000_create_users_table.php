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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('email',191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone',255)->nullable();
            $table->string('image',255)->nullable(); // Lưu đường dẫn file ảnh
            $table->string('password');
            $table->rememberToken();

            // Vai trò: Ví dụ 0=Customer, 1=Staff, 2=Admin
            $table->tinyInteger('role')->default(0)->comment('0: Customer, 1: Admin, 21: Thu ngân, 22: Pha chế');

            // Mã nhân viên (nếu có)
            $table->string('employee_id')->nullable()->unique()->comment('Mã nhân viên (nếu có)');

            // Trạng thái tài khoản
            $table->tinyInteger('status')->default(1)->comment('0: Vô hiệu hóa, 1: Hoạt động, 2: Bị khóa');
            $table->decimal('salary_per_day', 15, 2)->default(0);
            $table->timestamps(); // created_at, updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
