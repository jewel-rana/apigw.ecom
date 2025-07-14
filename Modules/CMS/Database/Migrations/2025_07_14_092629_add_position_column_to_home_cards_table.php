<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionColumnToHomeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_cards', function (Blueprint $table) {
            if(!Schema::hasColumn('home_cards', 'position')) {
                $table->integer('position')->default(0)->index();
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
        Schema::table('home_cards', function (Blueprint $table) {
            if(Schema::hasColumn('home_cards', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
}
