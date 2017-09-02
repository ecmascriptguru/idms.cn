<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperatingCompanyIdIntoDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->integer('operating_company_id')->nullable()->unsigned()->after('id');
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign('operating_company_id');
            $table->dropColumn('operating_company_id');
        });
    }
}
