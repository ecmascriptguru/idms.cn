<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatingCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operating_companies', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('operating_companies');
    }
}
