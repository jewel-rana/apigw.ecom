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
        Schema::create('menu_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus');
            $table->foreignId('menu_item_id')->constrained();
            $table->string('language')->default('en');
            $table->string('name');
            $table->tinyText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_attributes');
    }
};
