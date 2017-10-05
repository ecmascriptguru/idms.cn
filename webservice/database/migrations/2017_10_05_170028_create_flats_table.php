<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flats', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned()->nullable();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->integer('building_id')->unsigned();
            $table->foreign('building_id')->references('id')->on('buildings');
            $table->integer('house_type_id')->unsigned();
            $table->foreign('house_type_id')->references('id')->on('house_types');
            $table->string('name', 20);
            $table->string('number', 10);
            $table->float('area');
            $table->string('owner_1_name', 10);
            $table->string('owner_1_document_type', 20);
            $table->string('owner_1_document_number', 20);
            $table->string('owner_1_phone', 20);
            $table->string('owner_2_name', 20);
            $table->string('owner_2_document_type', 20);
            $table->string('owner_2_document_number', 20);
            $table->string('owner_2_phone', 20);

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
        Schema::drop('flats');
    }
}
