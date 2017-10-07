<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckInDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_in_devices', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->integer('check_in_type_id')->unsigned();
            $table->foreign('check_in_type_id')->references('id')->on('check_in_types');
            $table->integer('check_in_position_id')->unsigned();
            $table->foreign('check_in_position_id')->references('id')->on('check_in_positions');
            $table->integer('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->integer('building_id')->unsigned();
            $table->foreign('building_id')->references('id')->on('buildings');
            $table->boolean('is_connected_to_elevator')->default(false);
            $table->string('mac_address', 20);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('check_in_devices');
    }
}
