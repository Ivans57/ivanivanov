<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //full access users are able to see the whole contents of chosen album.
            $table->json('en_albums_full_access')->nullable();
            //limited access users are not able to see the whole contents of chosen album.
            $table->json('en_albums_limited_access')->nullable();
            //full access users are able to see the whole contents of chosen album.
            $table->json('ru_albums_full_access')->nullable();
            //limited access users are not able to see the whole contents of chosen album.
            $table->json('ru_albums_limited_access')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_albums');
    }
}
