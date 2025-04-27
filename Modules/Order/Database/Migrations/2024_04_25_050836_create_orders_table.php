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
            if(!Schema::hasTable('orders')) {
                $table->id()->startingValue(10000000001);
                $table->uuid()->unique();
                $table->foreignId('customer_id')->nullable()->constrained();
                $table->integer('total_qty')->default(1);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('coupon_discount', 10, 2)->default(0);
                $table->decimal('total_payable', 10, 2)->default(0);
                $table->enum('status', ['pending', 'processing', 'cancelled', 'complete', 'failed'])
                    ->default('pending')
                    ->index();
                $table->timestamps();
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
