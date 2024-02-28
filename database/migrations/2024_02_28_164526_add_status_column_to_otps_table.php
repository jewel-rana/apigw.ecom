<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if(!Schema::hasColumn('otps', 'status')) {
                $table->tinyInteger('status')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if(Schema::hasColumn('otps', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
