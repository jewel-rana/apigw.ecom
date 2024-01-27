<?php

use App\Constants\AuthConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->startingValue(1000001);
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('invoice_no')->index();
            $table->foreignId('promotion_id')->constrained();
            $table->foreignId('promotion_objective_id')->constrained();
            $table->integer('promotion_period')->default(AuthConstant::DEFAULT_PROMOTION_PERIOD);
            $table->float('amount', 12, 2)->default(0);
            $table->string('location')->default('all');
            $table->text('divisions');
            $table->integer('min_age')->default(13);
            $table->integer('max_age')->default(65);
            $table->enum('status',
                [
                    'Pending', 'Active', 'Completed', 'Cancelled', 'Hold'
                ])
                ->default('Pending')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['created_at', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
