<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_standards', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->integer('house_type_id')->unsigned();
            $table->foreign('house_type_id')->references('id')->on('house_types');
            $table->float('property_management_fee')->default(1);
            $table->float('water_fee')->default(1);
            $table->float('electricity_fee')->default(1);
            $table->float('parking_fee')->default(100);
            $table->float('gas_fee')->default(0);
            $table->float('heating_fee')->default(0);
            $table->float('internet_fee')->default(0);
            $table->integer('custom_fee_1_type_id')->unsigned()->nullable();
            $table->foreign('custom_fee_1_type_id')->references('id')->on('custom_fee_types');
            $table->string('custom_fee_1_name', 20)->nullable();
            $table->float('custom_fee_1_rate')->nullable();
            $table->integer('custom_fee_2_type_id')->unsigned()->nullable();
            $table->foreign('custom_fee_2_type_id')->references('id')->on('custom_fee_types');
            $table->string('custom_fee_2_name', 20)->nullable();
            $table->float('custom_fee_2_rate')->nullable();
            $table->integer('custom_fee_3_type_id')->unsigned()->nullable();
            $table->foreign('custom_fee_3_type_id')->references('id')->on('custom_fee_types');
            $table->string('custom_fee_3_name', 20)->nullable();
            $table->float('custom_fee_3_rate')->nullable();

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
        Schema::drop('fee_standards');
    }
}
