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
        Schema::create('socialites', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->index();
            $table->string('social_id')->index();
            $table->string('provider')->index();
            $table->json('payload');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->boolean('revoked')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socialites');
    }
};
