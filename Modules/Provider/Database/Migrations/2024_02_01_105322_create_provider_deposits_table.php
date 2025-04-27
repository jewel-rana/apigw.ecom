<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained();
            $table->string('voucher_number')->index();
            $table->float('amount', 12,2)->default(0);
            $table->string('currency')->default('iqd')->index();
            $table->float('currency_rate', 12, 2)->default(1);
            $table->float('amount_iqd', 12, 2)->default(0);
            $table->float('previous_balance', 12, 2)->default(0);
            $table->float('current_balance', 12, 2)->default(0);
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_deposits');
    }
}
