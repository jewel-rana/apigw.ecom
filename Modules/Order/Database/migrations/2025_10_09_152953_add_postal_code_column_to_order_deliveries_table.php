<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostalCodeColumnToOrderDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_deliveries', function (Blueprint $table) {
            if(!Schema::hasColumn('order_deliveries', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('address');
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
        Schema::table('order_deliveries', function (Blueprint $table) {
            if(Schema::hasColumn('order_deliveries', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
        });
    }
}
