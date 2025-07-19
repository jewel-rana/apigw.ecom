<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            if(!Schema::hasColumn('providers', 'status')) {
                $table->enum('status', ['Active', 'Inactive'])->default('Active')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            if(Schema::hasColumn('providers', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
