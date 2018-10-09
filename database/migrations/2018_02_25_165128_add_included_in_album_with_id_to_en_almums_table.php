<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncludedInAlbumWithIdToEnAlmumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('en_albums', function (Blueprint $table) {
            //
            $table->integer('included_in_album_with_id')->unsigned()->nullable();
            $table->foreign('included_in_album_with_id')->references('id')->on('en_albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('en_albums', function (Blueprint $table) {
            //
            $table->dropForeign('en_albums_included_in_album_with_id_foreign');
            $table->dropColumn('included_in_album_with_id');
        });
    }
}
