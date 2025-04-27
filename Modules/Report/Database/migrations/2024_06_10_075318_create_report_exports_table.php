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
        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->foreignId('user_id')->constrained();
            $table->json('criteria')->nullable();
            $table->string('attachment')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])
                ->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_exports');
    }
};
