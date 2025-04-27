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
        Schema::table('order_items', function (Blueprint $table) {
            if(!Schema::hasColumn('order_items', 'service_type_id')) {
                $table->foreignId('service_type_id')->default(1)->constrained();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if(Schema::hasColumn('order_items', 'service_type_id')) {
                $table->dropColumn('service_type_id');
            }
        });
    }
};
