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
        Schema::table('orders', function (Blueprint $table) {
            if(!Schema::hasColumn('orders', 'country_id')) {
                $table->foreignId('country_id')->nullable()->constrained();
            }

            if(!Schema::hasColumn('orders', 'city_id')) {
                $table->foreignId('city_id')->nullable()->constrained();
            }

            if(!Schema::hasColumn('orders', 'code')) {
                $table->string('code', 15)->nullable();
            }

            if(!Schema::hasColumn('orders', 'address')) {
                $table->tinyText('address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumns('orders', ['country_id', 'city_id', 'code', 'address'])) {
                $table->dropColumn(['country_id', 'city_id', 'code', 'address']);
            }
        });
    }
};
