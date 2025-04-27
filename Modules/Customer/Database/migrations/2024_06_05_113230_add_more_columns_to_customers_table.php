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
        Schema::table('customers', function (Blueprint $table) {
            if(!Schema::hasColumns('customers', ['country_id', 'city_id', 'code'])) {
//                $table->foreignId('country_id')->nullable()->constrained();
//                $table->foreignId('city_id')->nullable()->constrained();
                $table->string('code', 15)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if(Schema::hasColumns('customers', ['country_id', 'city_id', 'code'])) {
                $table->dropColumn(['country_id', 'city_id', 'code']);
            }
        });
    }
};
