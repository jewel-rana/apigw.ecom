<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Constants\ProductConstant;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('provider_id')->nullable()->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('strike_price', 15, 2);
            $table->string('status')->default(ProductConstant::STATUS_ACTIVE);
            $table->string('remarks')->nullable();
            $table->string('thumbnail')->nullable();
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
        Schema::dropIfExists('products');
    }
}
