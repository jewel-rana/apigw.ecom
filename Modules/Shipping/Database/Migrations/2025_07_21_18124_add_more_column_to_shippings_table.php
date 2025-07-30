<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnToShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            if(!Schema::hasColumn('shippings', 'code')) {
                $table->string('code')->nullable();
            }
            if(Schema::hasColumn('shippings', 'code')) {
                $table->string('code')->nullable()->change();
            }
            if(!Schema::hasColumn('shippings', 'position')) {
                $table->string('position')->default(0)->index();
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
        Schema::table('shippings', function (Blueprint $table) {
            if(Schema::hasColumn('shippings', 'code')) {
                $table->dropColumn('code');
            }
            if(Schema::hasColumn('shippings', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
}
