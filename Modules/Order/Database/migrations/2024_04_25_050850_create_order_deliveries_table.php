<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('shipping_id')->constrained();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('delivery_man_id')->nullable()->constrained('users', 'id');
            $table->string('delivery_man_name')->nullable();
            $table->string('delivery_man_mobile')->nullable();
            $table->decimal('cash_received', 10, 2)->default(0);
            $table->decimal('cash_returned', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['Pending', 'Collected', 'Processing', 'Failed', 'Delivered'])->default('Pending')->index();
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
        Schema::dropIfExists('order_deliveries');
    }
}
