<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ru_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword')->unique();
            $table->string('article_title');
            $table->text('article_description');
            $table->text('article_body');
            $table->string('article_author');
            $table->string('article_source');
            $table->integer('folder_id')->unsigned();
            $table->foreign('folder_id')->references('id')->on('ru_folders')->onDelete('cascade');
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
        Schema::dropIfExists('ru_articles');
    }
}
