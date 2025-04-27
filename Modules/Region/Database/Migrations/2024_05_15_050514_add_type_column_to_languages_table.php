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
        Schema::table('languages', function (Blueprint $table) {
            if(!Schema::hasColumn('languages', 'type')) {
                $table->enum('type', ['rtl', 'ltr'])->default('ltr');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            if(Schema::hasColumn('languages', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
