<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionColumnToBannerMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_media', function (Blueprint $table) {
            $table->string('btn_color')->nullable()->change();
            if(!Schema::hasColumn('banner_media', 'position')) {
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
        Schema::table('banner_media', function (Blueprint $table) {
            if(Schema::hasColumn('banner_media', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
}
