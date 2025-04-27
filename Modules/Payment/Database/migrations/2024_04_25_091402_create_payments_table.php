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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->startingValue(10000001);
            $table->foreignId('order_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('gateway_id')->constrained();
            $table->decimal('amount', 11, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled', 'declined'])
                ->default('pending')
                ->index();
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
