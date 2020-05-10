<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnMainLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_main_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->unique();
            $table->string('web_link_name');
            $table->string('admin_web_link_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_main_links');
    }
}
