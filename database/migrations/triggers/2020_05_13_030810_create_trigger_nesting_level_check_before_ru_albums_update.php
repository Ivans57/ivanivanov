<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNestingLevelCheckBeforeRuAlbumsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `nesting_level_check_before_ru_albums_update` BEFORE UPDATE ON `ru_albums` FOR EACH ROW
            BEGIN
		IF ((NEW.included_in_album_with_id <=> OLD.included_in_album_with_id) = 0) THEN
                    IF (NEW.included_in_album_with_id IS NOT NULL) THEN
			SET @potential_parents_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = NEW.included_in_album_with_id);
			IF (@potential_parents_nesting_level > 6) THEN
                            SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The nesting level of the item can not be more than 7.";
			ELSE
                            SET @nesting_level_check := CheckNestingLevel(OLD.id, @potential_parents_nesting_level, "ru_albums_data");
                            IF (@nesting_level_check = FALSE) THEN
				SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The nesting level of the album cannot be more than 7.";
                            END IF;
			END IF;
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
        DB::unprepared('DROP TRIGGER `nesting_level_check_before_ru_albums_update`');
    }
}
