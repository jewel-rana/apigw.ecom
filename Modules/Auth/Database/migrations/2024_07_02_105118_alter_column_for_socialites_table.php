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
        Schema::table('socialites', function (Blueprint $table) {
            if(Schema::hasColumns('socialites', ['access_token', 'refresh_token'])) {
                $table->text('access_token')->nullable()->change();
                $table->text('refresh_token')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
