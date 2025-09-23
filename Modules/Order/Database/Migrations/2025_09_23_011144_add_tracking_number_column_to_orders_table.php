<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingNumberColumnToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if(!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->index();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumn('orders', 'tracking_number')) {
                $table->dropColumn('tracking_number');
            }
        });
    }
}
