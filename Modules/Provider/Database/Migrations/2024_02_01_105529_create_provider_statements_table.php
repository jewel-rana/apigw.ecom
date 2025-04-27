<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderStatementsTable extends Migration
{
    /**d
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained();
            $table->string('model')->default("\Modules\Transaction\Entities\Transaction::class");
            $table->unsignedBigInteger('model_id');
            $table->float('amount', 12, 2);
            $table->float('balance', 12, 2);
            $table->enum('type', ['credit', 'debit'])->default('debit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_statements');
    }
}
