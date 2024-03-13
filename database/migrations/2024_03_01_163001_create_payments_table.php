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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->float('amount')->default(0);
            $table->string('payment_method')->default('bkash');
            $table->string('gateway_trx_id')->nullable()->index();
            $table->json('gateway_response')->nullable();
            $table->enum('status', ['Pending', 'Success', 'Failed', 'Cancelled', 'Refunded'])
                ->default('Pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
