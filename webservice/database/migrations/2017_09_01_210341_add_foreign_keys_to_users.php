<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('operating_company_id')->nullable()->unsigned()->after('id');
            $table->foreign('operating_company_id')->references('id')->on('operating_companies');

            $table->integer('property_company_id')->nullable()->unsigned()->after('operating_company_id');
            $table->foreign('property_company_id')->references('id')->on('property_companies');

            $table->integer('district_id')->nullable()->unsigned()->after('property_company_id');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('operating_company_id');
            $table->dropColumn('operating_company_id');

            $table->dropForeign('property_company_id');
            $table->dropColumn('property_company_id');

            $table->dropForeign('district_id');
            $table->dropColumn('district_id');
        });
    }
}
