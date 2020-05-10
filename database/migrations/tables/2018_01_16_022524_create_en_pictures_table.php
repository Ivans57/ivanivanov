<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->unique();
            $table->string('picture_caption', 50);
            $table->integer('album_id')->unsigned();
            $table->foreign('album_id')->references('id')->on('en_albums')->onDelete('cascade');
            $table->timestamps();
            $table->string('file_extension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('en_pictures', function (Blueprint $table) {
            $table->dropForeign('en_pictures_album_id_foreign');
            $table->dropColumn('album_id');
        });
        Schema::dropIfExists('en_pictures');
    }
}
