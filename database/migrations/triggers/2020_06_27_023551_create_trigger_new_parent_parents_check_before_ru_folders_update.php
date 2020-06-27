<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNewParentParentsCheckBeforeRuFoldersUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `new_parent_parents_check_before_ru_folders_update` BEFORE UPDATE ON `ru_folders` FOR EACH ROW
            BEGIN				
		IF (NEW.included_in_folder_with_id = OLD.id) 
                    THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The destination folder is the same as the source folder.";
		ELSE 
                    CALL PotentialParentsParentCheck(OLD.id, NEW.included_in_folder_with_id, "ru_folders");
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
        DB::unprepared('DROP TRIGGER `new_parent_parents_check_before_ru_folders_update`');
    }
}