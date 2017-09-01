<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyComapniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operating_company_id')->unsigned();
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');
            $table->string('name', 50);
            $table->string('short_name', 10);
            $table->string('address', 255);
            $table->string('phone', 15);
            $table->string('contact', 10);
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
        Schema::table('property_companies', function (Blueprint $table) {
            $table->dropForeign('operating_company_id');
        });
    }
}
