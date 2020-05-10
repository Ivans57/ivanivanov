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
            $table->string('keyword', 50)->unique();
            $table->string('folder_name', 50)->unique();
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
        Schema::table('en_folders', function (Blueprint $table) {
            $table->dropForeign('en_folders_included_in_folder_with_id_foreign');
            $table->dropColumn('included_in_folder_with_id');
        });
        Schema::dropIfExists('en_folders');
    }
}
