<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnMainLinksUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_main_links_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('links_id')->unsigned()->unique();
            $table->foreign('links_id')->references('id')->on('en_main_links')->onDelete('cascade');
            //full access users are able to see the whole contents of chosen section.
            $table->json('full_access_users')->nullable();
            //limited access users are not able to see the whole contents of chosen section.
            $table->json('limited_access_users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_main_links_users');
    }
}
