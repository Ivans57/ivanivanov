<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertIntoEnMainLinksUsersTableAfterEnMainLinksInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER `insert_into_en_main_links_users_table_after_en_main_links_insert` AFTER INSERT ON `en_main_links` FOR EACH ROW
                BEGIN
                    INSERT INTO en_main_links_users (links_id) VALUES (NEW.id);	
                END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `insert_into_en_main_links_users_table_after_en_main_links_insert`');
    }
}
