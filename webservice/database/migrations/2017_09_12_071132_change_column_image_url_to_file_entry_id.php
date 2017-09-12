<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnImageUrlToFileEntryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_advertisements', function (Blueprint $table) {
            $table->dropColumn('image_url');
            $table->integer('file_entry_id')->unsigned()->after('title')->nullable();
            $table->foreign('file_entry_id')->references('id')->on('file_entries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_advertisements', function (Blueprint $table) {
            $table->text('image_url')->nullable();
            $table->dropColumn('file_entry_id');
            $table->dropForeign('app_advertisements_file_entry_id_foreign');
        });
    }
}
