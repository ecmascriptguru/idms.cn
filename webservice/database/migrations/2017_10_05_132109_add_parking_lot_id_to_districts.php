<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParkingLotIdToDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function(Blueprint $table) {
            $table->integer('parking_lot_id')->unsigned()->nullable();
            $table->foreign('parking_lot_id')->references('id')->on('parking_lots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function(Blueprint $table) {
            $table->dropForeign('districts_parking_log_id_foreign');
            $table->dropColumn('parking_lot_id');
        });
    }
}
