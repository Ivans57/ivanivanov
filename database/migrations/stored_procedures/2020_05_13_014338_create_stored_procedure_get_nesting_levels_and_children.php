<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureGetNestingLevelsAndChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE GetNestingLevelsAndChildren(IN _items_id INT, IN _table_name VARCHAR(45), OUT _items_nesting_level INT, OUT _children JSON)
            BEGIN
		CASE _table_name
                    WHEN "en_albums_data" THEN 
                        SET _items_nesting_level := (SELECT nesting_level FROM en_albums_data WHERE items_id = _items_id);
                    WHEN "ru_albums_data" THEN 
                        SET _items_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = _items_id);
                    WHEN "en_folders_data" THEN 
                        SET _items_nesting_level := (SELECT nesting_level FROM en_folders_data WHERE items_id = _items_id);
                    WHEN "ru_folders_data" THEN 
                        SET _items_nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = _items_id);
		END CASE;
	
                CASE _table_name
                    WHEN "en_albums_data" THEN 
                        SET _children := (SELECT children FROM en_albums_data WHERE items_id = _items_id);
                    WHEN "ru_albums_data" THEN 
                        SET _children := (SELECT children FROM ru_albums_data WHERE items_id = _items_id);
                    WHEN "en_folders_data" THEN 
                        SET _children := (SELECT children FROM en_folders_data WHERE items_id = _items_id);
                    WHEN "ru_folders_data" THEN 
                        SET _children := (SELECT children FROM ru_folders_data WHERE items_id = _items_id);
		END CASE;			
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
        DB::unprepared('DROP PROCEDURE GetNestingLevelsAndChildren');
    }
}
