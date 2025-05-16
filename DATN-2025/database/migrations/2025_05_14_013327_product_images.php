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
    Schema::create('product_images', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('sanphams')->onDelete('cascade');
        $table->string('image_url', 255);
        $table->boolean('is_primary')->default(false);
        $table->foreignId('size_id')->nullable()->constrained('size')->onDelete('set null');
        $table->foreignId('topping_id')->nullable()->constrained('topping')->onDelete('set null');
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
