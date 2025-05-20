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
        Schema::create('SizeController', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('sanphams');
        $table->string('size')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->foreignId('topping_id')->constrained('topping')->onDelete('cascade');
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
