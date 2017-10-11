<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentityAccountTypeIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->integer('account_type_id')->unsigned()->nullable()->after('operating_company_id');
            $table->foreign('account_type_id')->references('id')->on('account_types');
            $table->string('identity_number', 20)->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_account_type_id_foreign');
            $table->dropColumn('account_type_id');
            $table->dropColumn('identity_number');
        });
    }
}
