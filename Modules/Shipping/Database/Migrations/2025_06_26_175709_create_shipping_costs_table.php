<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_id')->constrained();
            $table->float('cost', 12, 2)->default(0);
            $table->double('extra_per_kg', 12, 2)->default(20);
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
        Schema::dropIfExists('shipping_costs');
    }
}
