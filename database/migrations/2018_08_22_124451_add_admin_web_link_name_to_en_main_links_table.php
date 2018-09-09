<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminWebLinkNameToEnMainLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('en_main_links', function (Blueprint $table) {
            //
            $table->string('admin_web_link_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('en_main_links', function (Blueprint $table) {
            //
            $table->dropColumn('admin_web_link_name');
        });
    }
}
