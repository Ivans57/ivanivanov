<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNestingLevelCheckBeforeEnAlbumsInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER nesting_level_check_before_en_albums_insert BEFORE INSERT ON `en_albums` FOR EACH ROW
            BEGIN
		IF (NEW.included_in_album_with_id) 
                    THEN CALL NestingLevelCheck(NEW.included_in_album_with_id, 0, @nesting_level);
                    IF (@nesting_level > 7) 
			THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The nesting level of the item can not be more than 8.";
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
        DB::unprepared('DROP TRIGGER `nesting_level_check_before_en_albums_insert`');
    }
}
