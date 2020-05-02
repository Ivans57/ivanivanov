<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerPreventEnMainLinksUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `prevent_en_main_links_update` BEFORE UPDATE ON `en_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                
                SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Can not update en_main_links table. Delete the record and insert the new one.";
              
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
        DB::unprepared('DROP TRIGGER `prevent_en_main_links_update`');
    }
}
