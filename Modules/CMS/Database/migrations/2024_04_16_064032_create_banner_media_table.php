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
        Schema::create('banner_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->constrained();
            $table->foreignId('media_id')->constrained()->on('medias');
            $table->string('title')->nullable();
            $table->string('slogan')->nullable();
            $table->tinyText('description')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('btn_url')->nullable();
            $table->boolean('is_periodical')->default(false);
            $table->timestamp('expire_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_media');
    }
};
