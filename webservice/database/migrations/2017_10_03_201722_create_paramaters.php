<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamaters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operating_company_id')->unsigned();
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');
            $table->integer('video_intercom_call_waiting_time')->default(30);
            $table->integer('adv_refresh_waiting_time')->default(60);
            $table->integer('device_connection_waiting_time')->default(10);
            $table->integer('direct_phone_call_waiting_time_limit')->default(60);
            $table->integer('password_input_waiting_time')->default(20);
            $table->integer('unit_number_length')->default(4);
            $table->integer('floor_length_number')->default(2);
            $table->integer('floor_length')->default(2);
            $table->integer('media_sound_volumn')->default(3);
            $table->integer('call_sound_volumn')->default(8);
            $table->integer('dial_ring_tones')->default(5);
            $table->integer('system_sound_volumn')->default(2);
            $table->integer('video_quality')->default(4);
            $table->integer('video_auto_adaption_network')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('parameters');
    }
}
