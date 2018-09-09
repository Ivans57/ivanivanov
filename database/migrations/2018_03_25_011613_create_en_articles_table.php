<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword')->unique();
            $table->string('article_title');
            $table->text('article_description');
            $table->text('article_body');
            $table->string('article_author');
            $table->string('article_source');
            $table->integer('folder_id')->unsigned();
            $table->foreign('folder_id')->references('id')->on('en_folders')->onDelete('cascade');
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
        Schema::dropIfExists('en_articles');
    }
}
