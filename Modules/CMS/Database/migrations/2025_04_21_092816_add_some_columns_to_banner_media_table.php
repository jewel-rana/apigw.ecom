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
        Schema::table('banner_media', function (Blueprint $table) {
            if(!Schema::hasColumn('banner_media', 'text_color')) {
                $table->string('text_color')->default('#ffffff');
            }

            if(!Schema::hasColumn('banner_media', 'btn_color')) {
                $table->string('btn_color')->default('#000000');
            }

            if(!Schema::hasColumn('banner_media', 'text_size')) {
                $table->enum('text_size', ['large', 'medium', 'small'])->default('large');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_media', function (Blueprint $table) {

            if(Schema::hasColumn('banner_media', 'text_color')) {
                $table->dropColumn('text_color');
            }

            if(Schema::hasColumn('banner_media', 'btn_color')) {
                $table->dropColumn('btn_color');
            }

            if(Schema::hasColumn('banner_media', 'text_size')) {
                $table->dropColumn('text_size');
            }
        });
    }
};
