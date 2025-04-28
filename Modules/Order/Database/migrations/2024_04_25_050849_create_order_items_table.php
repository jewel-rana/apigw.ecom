<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id()->startingValue('10000001');
                $table->foreignId('order_id')->constrained();
                $table->foreignId('operator_id')->constrained();
                $table->foreignId('bundle_id')->nullable()->constrained();
                $table->integer('qty')->default(1);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('coupon_discount', 10, 2)->default(0);
                $table->decimal('total_price', 10, 2)->default(0);
                $table->enum('status', ['pending', 'success', 'cancelled', 'refunded', 'failed'])
                    ->default('pending')
                    ->index();
                $table->json('data')->nullable();
                $table->timestamps();
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
