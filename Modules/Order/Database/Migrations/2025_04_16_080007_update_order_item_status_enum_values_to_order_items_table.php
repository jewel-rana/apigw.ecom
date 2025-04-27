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
            if(Schema::hasColumn('order_items', 'status')) {
                $table->enum('status', ['pending', 'processing', 'initiated', 'unstable', 'success', 'cancelled', 'refunded', 'failed'])
                    ->default('pending')
                    ->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

        });
    }
};
