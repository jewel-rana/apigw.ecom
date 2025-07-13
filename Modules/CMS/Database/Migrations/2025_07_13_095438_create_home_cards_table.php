<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->string('title');
            $table->tinyText('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('border_color')->nullable();
            $table->string('variant_color')->nullable();
            $table->string('url')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_cards');
    }
}
