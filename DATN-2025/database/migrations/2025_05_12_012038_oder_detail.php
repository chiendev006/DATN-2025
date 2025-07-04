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
       Schema::create('order_detail', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('order');
        $table->foreignId('product_id')->constrained('sanphams');
        $table->string('product_name');
        $table->decimal('product_price', 10, 2);
        $table->integer('quantity');
        $table->decimal('total', 10, 2);
        $table->unsignedBigInteger('size_id')->nullable();
        $table->string('topping_id')->nullable();
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->softDeletes();
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
