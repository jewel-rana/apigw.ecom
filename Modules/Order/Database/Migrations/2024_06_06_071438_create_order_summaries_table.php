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
        Schema::create('order_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained();
            $table->foreignId('operator_id')->constrained();
            $table->foreignId('bundle_id')->nullable()->constrained();
            $table->date('selling_date')->index();
            $table->bigInteger('success_items')->default(0);
            $table->bigInteger('failed_items')->default(0);
            $table->float('success_amount')->default(0);
            $table->float('failed_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_summaries');
    }
};
