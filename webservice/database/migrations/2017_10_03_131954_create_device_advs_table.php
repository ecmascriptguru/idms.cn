<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceAdvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_advertisements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operating_company_id')->unsigned();
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');
            $table->string('name', 20);
            $table->integer('file_entry_id')->unsigned()->nullable();
            $table->foreign('file_entry_id')->references('id')->on('file_entries');
            $table->string('from', 10);
            $table->string('to', 10);
            $table->string('title', 50)->nullable();
            $table->smallInteger('status')->default(1);
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
        Schema::drop('device_advertisements');
    }
}
