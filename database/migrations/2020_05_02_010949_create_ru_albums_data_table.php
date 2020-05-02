<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuAlbumsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ru_albums_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('items_id')->unsigned()->unique();
            $table->foreign('items_id')->references('id')->on('ru_albums')->onDelete('cascade');
            $table->integer('nesting_level')->unsigned();
            $table->json('parents')->nullable();
            $table->json('children')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ru_albums_data');
    }
}
