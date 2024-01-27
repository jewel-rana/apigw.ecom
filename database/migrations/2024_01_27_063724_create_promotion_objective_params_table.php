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
            $table->enum('type', ['text', 'number', 'checkbox', 'radio', 'select', 'textarea'])->default('text');
            $table->string('key');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->text('items')->nullable();
            $table->boolean('is_required')->default(1);
            $table->integer('min_length')->default(2);
            $table->integer('max_length')->default(32);
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
