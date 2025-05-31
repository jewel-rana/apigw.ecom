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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id()->startingValue(10000000001);
                $table->uuid()->unique();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('customer_id')->nullable()->constrained();
                $table->integer('total_qty')->default(1);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('coupon_discount', 10, 2)->default(0);
                $table->decimal('total_payable', 10, 2)->default(0);
                $table->string('status')->default('pending')->index();
                $table->text('notes')->nullable();
                $table->text('remarks')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
