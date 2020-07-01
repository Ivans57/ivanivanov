<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ru_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->unique();
            $table->string('album_name', 50);
            $table->timestamps();
            $table->boolean('is_visible');
            $table->integer('included_in_album_with_id')->unsigned()->nullable();
            $table->foreign('included_in_album_with_id')->references('id')->on('ru_albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ru_albums', function (Blueprint $table) {
            $table->dropForeign('ru_albums_included_in_album_with_id_foreign');
            $table->dropColumn('included_in_album_with_id');
        });
        Schema::dropIfExists('ru_albums');
    }
}
