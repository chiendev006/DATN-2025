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
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('name', 255);         
        $table->string('address', 255);      
        $table->string('phone', 15);       
        $table->enum('payment_method', ['cash', 'banking', 'momo'])->default('cash');
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->decimal('total', 10, 2);     
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
