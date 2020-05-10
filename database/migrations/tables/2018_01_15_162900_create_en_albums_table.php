<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->unique();
            $table->string('album_name', 50)->unique();
            $table->timestamps();
            $table->boolean('is_visible');
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
        Schema::dropIfExists('en_albums');
    }
}
