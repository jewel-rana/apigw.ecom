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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->enum('status', [
                'Pending', 'Active', 'Inactive'
            ])
                ->default('Pending')->index();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
