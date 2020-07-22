<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ru_pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->unique();
            $table->string('picture_caption', 50);
            $table->integer('included_in_album_with_id')->unsigned();
            $table->foreign('included_in_album_with_id')->references('id')->on('ru_albums')->onDelete('cascade');
            $table->timestamps();
            $table->string('file_name');
            $table->boolean('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ru_pictures', function (Blueprint $table) {
            $table->dropForeign('ru_pictures_included_in_album_with_id_foreign');
            $table->dropColumn('included_in_album_with_id');
        });
        Schema::dropIfExists('ru_pictures');
    }
}
