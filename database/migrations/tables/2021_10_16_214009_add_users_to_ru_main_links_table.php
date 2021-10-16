<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersToRuMainLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ru_main_links', function (Blueprint $table) {
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
        Schema::table('ru_main_links', function (Blueprint $table) {
            $table->dropColumn('full_access_users');
            $table->dropColumn('limited_access_users');
        });
    }
}
