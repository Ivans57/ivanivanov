<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNestingLevelCheckBeforeRuFoldersInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER nesting_level_check_before_ru_folders_insert BEFORE INSERT ON `ru_folders` FOR EACH ROW
            BEGIN
		IF (NEW.included_in_folder_with_id IS NOT NULL) THEN 					
                    IF ((SELECT nesting_level FROM ru_folders_data WHERE items_id = NEW.included_in_folder_with_id) > 6) 
			THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The nesting level of the item can not be more than 7.";
                    END IF;
		END IF;				
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
        DB::unprepared('DROP TRIGGER `nesting_level_check_before_ru_folders_insert`');
    }
}