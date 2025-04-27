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
        Schema::table('refunds', function (Blueprint $table) {
            if(!Schema::hasColumn('refunds', 'payment_id')) {
                $table->foreignId('payment_id')->nullable()->constrained()->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            if(Schema::hasColumn('refunds', 'payment_id')) {
                $table->dropForeign('refunds_payment_id_foreign');
            }
        });
    }
};
