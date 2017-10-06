<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingReferenceToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function(Blueprint $table) {
            $table->integer('building_id')->unsigned()->after('district_id');
            $table->foreign('building_id')->references('id')->on('buildings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function(Blueprint $table) {
            $table->dropForeign('bills_building_id_foreign');
            $table->dropColumn('building_id');
        });
    }
}
