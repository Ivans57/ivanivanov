<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedurePotetialParentsParentCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE PotetialParentsParentCheck(IN _being_changed_items_id INT, IN _potential_parents_id INT, IN _table_name VARCHAR(45))
            BEGIN
                DECLARE potential_parents_parent_id INT;
                
                SET max_sp_recursion_depth = 8;
				
                CASE _table_name
                    WHEN "en_albums" THEN SET potential_parents_parent_id := (SELECT included_in_album_with_id FROM en_albums WHERE id = _potential_parents_id);
                    WHEN "ru_albums" THEN SET potential_parents_parent_id := (SELECT included_in_album_with_id FROM ru_albums WHERE id = _potential_parents_id);
                    WHEN "en_folders" THEN SET potential_parents_parent_id := (SELECT included_in_folder_with_id FROM en_folders WHERE id = _potential_parents_id);
                    WHEN "ru_folders" THEN SET potential_parents_parent_id := (SELECT included_in_folder_with_id FROM ru_folders WHERE id = _potential_parents_id);
                END CASE;
				
                IF (potential_parents_parent_id = _being_changed_items_id) THEN
                    IF (_table_name = "en_albums" OR _table_name = "ru_albums")
                        THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The destination album is a child album of the source album.";
                    ELSEIF (_table_name = "en_folders" OR _table_name = "ru_folders") 
                        THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The destination folder is a child folder of the source folder.";
                    END IF;
                ELSEIF (potential_parents_parent_id) AND (potential_parents_parent_id != _being_changed_items_id) 
                    THEN CALL PotetialParentsParentCheck(_being_changed_items_id, potential_parents_parent_id, _table_name);
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
        DB::unprepared('DROP PROCEDURE PotetialParentsParentCheck');
    }
}
