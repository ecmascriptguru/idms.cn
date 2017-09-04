<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppAdvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_advertisements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operating_company_id')->unsigned();
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');
            $table->string('title', 10);
            $table->text('image_url')->nullable();
            $table->string('image_title', 50)->nullable();
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
        Schema::dropIfExists('app_advertisements');
    }
}
