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
        Schema::table('payments', function (Blueprint $table) {
            if(!Schema::hasColumn('payments', 'gateway_payment_id')) {
                $table->string('gateway_payment_id')->nullable()->index();
                $table->string('gateway_refund_trx_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if(Schema::hasColumn('payments', 'gateway_payment_id')) {
                $table->dropColumn(['gateway_payment_id', 'gateway_refund_trx_id']);
            }
        });
    }
};
