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
            if (!Schema::hasColumn('orders', 'is_refund_initiated')) {
                $table->boolean('is_refund_initiated')->default(false)->index();
            }
            if (!Schema::hasColumn('orders', 'is_refunded')) {
                $table->boolean('is_refunded')->default(false)->index();
            }
            if (!Schema::hasColumn('orders', 'remarks')) {
                $table->string('remarks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'is_refund_initiated')) {
                $table->dropColumn('is_refund_initiated');
            }
            if (Schema::hasColumn('orders', 'is_refunded')) {
                $table->dropColumn('is_refunded');
            }
        });
    }
};
