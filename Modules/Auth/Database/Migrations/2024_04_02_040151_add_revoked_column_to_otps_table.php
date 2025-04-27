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
        Schema::table('otps', function (Blueprint $table) {
            if(!Schema::hasColumns('otps', ['revoked', 'email', 'channels'])) {
                $table->boolean('revoked')->after('code')
                    ->default(false)
                    ->index();
                $table->string('email')->after('id')->index()->nullable();
                $table->json('channels')->nullable()->after('revoked');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if(Schema::hasColumns('otps', ['revoked', 'email', 'channels'])) {
                $table->dropColumn(['revoked', 'email', 'channels']);
            }
        });
    }
};
