<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword')->unique();
            $table->string('folder_name')->unique();
            $table->timestamps();
            $table->boolean('is_visible')->nullable();
            $table->integer('included_in_folder_with_id')->unsigned()->nullable();
            $table->foreign('included_in_folder_with_id')->references('id')->on('en_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_folders');
    }
}
