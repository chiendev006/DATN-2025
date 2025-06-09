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
            $table->foreignId('address_id')->constrained('address')->onDelete('cascade'); // Link to the address table
            // You might still want to store these for detailed order history or display if needed
            $table->string('address_detail')->nullable(); // e.g., house number, street
            $table->string('district_name')->nullable(); // To store the district name at the time of order
            $table->decimal('shipping_fee')->default(0);
            $table->enum('payment_method', ['cash', 'banking'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('cancel_reason', 255)->nullable();
            $table->decimal('total', 10, 2);
            $table->uuid('transaction_id')->nullable();
            $table->string('pay_status', 10)->default('0');
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

