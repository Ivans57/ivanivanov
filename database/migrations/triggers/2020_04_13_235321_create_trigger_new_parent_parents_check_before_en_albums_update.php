<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNewParentParentsCheckBeforeEnAlbumsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `new_parent_parents_check_before_en_albums_update` BEFORE UPDATE ON `en_albums` FOR EACH ROW
            BEGIN				
		IF (NEW.included_in_album_with_id = OLD.id) 
                    THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The destination album is the same as the source album.";
		ELSE 
                    CALL PotentialParentsParentCheck(OLD.id, NEW.included_in_album_with_id, "en_albums");
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
        DB::unprepared('DROP TRIGGER `new_parent_parents_check_before_en_albums_update`');
    }
}
