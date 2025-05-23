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
        Schema::create('cart_detail', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cart_id')->constrained('cart');
        $table->foreignId('product_id')->constrained('sanphams');
        $table->foreignId('size_id')->constrained('product_attributess');
        $table->string('topping_id')->nullable();
        $table->foreign('topping_id')->references('id')->on('product_topping');
        $table->integer('quantity');
        $table->timestamps();
});

    }


    public function down(): void
    {
        //
    }
};
