<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guards', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_lot_id')->unsigned();
            $table->foreign('parking_lot_id')->references('id')->on('parking_lots');
            $table->string('name', 20);

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
        Schema::drop('guards');
    }
}
