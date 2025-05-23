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
            if(!Schema::hasColumn('socialites', 'customer_id')) {
                $table->foreignId('customer_id')->after('id')->nullable()->constrained();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('socialites', function (Blueprint $table) {
            if(Schema::hasColumn('socialites', 'customer_id')) {
                $table->dropColumn('customer_id');
            }
        });
    }
};
