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
            $table->string('keyword')->unique();
            $table->string('picture_caption');
            $table->integer('album_id')->unsigned();
            $table->foreign('album_id')->references('id')->on('ru_albums')->onDelete('cascade');
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
        Schema::table('ru_pictures', function (Blueprint $table) {
            $table->dropForeign('ru_pictures_album_id_foreign');
            $table->dropColumn('album_id');
        });
        Schema::dropIfExists('ru_pictures');
    }
}
