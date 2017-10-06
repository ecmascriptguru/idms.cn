<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->integer('flat_id')->unsigned();
            $table->foreign('flat_id')->references('id')->on('flats');
            $table->integer('monthly_bill_notification_id')->unsigned();
            $table->foreign('monthly_bill_notification_id')->references('id')->on('monthly_bill_notifications');
            $table->float('area')->default(50);
            $table->float('water')->default(0);
            $table->float('electricity')->default(0);
            $table->float('cars')->default(0);
            $table->float('gas')->default(0);
            $table->float('heating')->default(0);
            $table->float('internet')->default(0);
            $table->float('total')->default(0);
            $table->boolean('is_paid')->default(false);

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
        Schema::drop('bills');
    }
}
