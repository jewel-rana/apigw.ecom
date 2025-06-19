<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('product_id')->constrained();
            $table->uuid('sku')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('strike_price', 12, 2)->default(0);
            $table->enum('status', ['pre-order','available','low','unavailable'])->default('unavailable');
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
        Schema::dropIfExists('product_variants');
    }
}
