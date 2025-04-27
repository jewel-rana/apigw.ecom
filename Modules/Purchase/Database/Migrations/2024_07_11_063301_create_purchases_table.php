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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained();
            $table->integer('quantity');
            $table->decimal('amount',12,2);
            $table->string('currency')->default('iqd');
            $table->decimal('exchange_rate')->default(1);
            $table->enum('status',['Pending','Completed','Canceled'])->default('Pending')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index('created_at');
        });
        DB::statement("ALTER TABLE purchases AUTO_INCREMENT = 1000001;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
