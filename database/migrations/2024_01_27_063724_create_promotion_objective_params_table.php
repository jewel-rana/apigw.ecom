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
        Schema::create('promotion_objective_params', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_objective_id')->constrained();
            $table->string('key');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->text('items');
            $table->enum('status', ['Active', 'Inactive'])->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_objective_params');
    }
};
