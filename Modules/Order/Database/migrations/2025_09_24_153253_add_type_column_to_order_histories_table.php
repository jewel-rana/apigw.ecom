<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnToOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_histories', function (Blueprint $table) {
            if(!Schema::hasColumn('order_histories', 'type')) {
                $table->string('type')->default('status')->index()->after('user_id');
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
        Schema::table('order_histories', function (Blueprint $table) {
            if(Schema::hasColumn('order_histories', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
