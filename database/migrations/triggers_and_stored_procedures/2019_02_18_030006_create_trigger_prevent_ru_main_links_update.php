<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerPreventRuMainLinksUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `prevent_ru_main_links_update` BEFORE UPDATE ON `ru_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                
                SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Can not update ru_main_links table. Delete the record and insert the new one.";
                
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
        DB::unprepared('DROP TRIGGER `prevent_ru_main_links_update`');
    }
}
