<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lanes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_lot_id')->unsigned();
            $table->foreign('parking_lot_id')->references('id')->on('parking_lots');
            $table->integer('guard_id')->unsigned();
            $table->foreign('guard_id')->references('id')->on('guards');
            $table->string('name', 20);
            $table->string('number', 20);
            $table->string('control_number', 20)->nullable();
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
        Schema::drop('lanes');
    }
}
