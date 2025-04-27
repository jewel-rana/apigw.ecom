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
        Schema::table('regions', function (Blueprint $table) {
            if(Schema::hasColumn('regions', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
            if(!Schema::hasColumn('regions', 'flag')) {
                $table->string('flag')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            if(Schema::hasColumn('regions', 'flag')) {
                $table->dropColumn('flag');
            }
        });
    }
};
