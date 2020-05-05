<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureUpdateNestingLevelInAlbumsOrFolderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE UpdateNestingLevelInAlbumsOrFolderData(IN _items_id INT, IN _new_parents_id INT, IN _children_without_current_item JSON, IN _children JSON, IN _table_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
		
		SET @new_nesting_level_array := GetNewNestingLevel(_items_id, _new_parents_id, _children_without_current_item, _children, _table_name);
			
                WHILE (_counter < JSON_LENGTH(_children)) DO

                    CASE _table_name
			WHEN "en_albums_data" THEN 
                            UPDATE en_albums_data SET nesting_level = JSON_EXTRACT(@new_nesting_level_array, CONCAT("$[",_counter,"]")) 
				WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
			WHEN "ru_albums_data" THEN 
                            UPDATE ru_albums_data SET nesting_level = JSON_EXTRACT(@new_nesting_level_array, CONCAT("$[",_counter,"]")) 
				WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
			WHEN "en_folders_data" THEN 
                            UPDATE en_folders_data SET nesting_level = JSON_EXTRACT(@new_nesting_level_array, CONCAT("$[",_counter,"]")) 
				WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
			WHEN "ru_folders_data" THEN 
                            UPDATE ru_folders_data SET nesting_level = JSON_EXTRACT(@new_nesting_level_array, CONCAT("$[",_counter,"]")) 
				WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
                    END CASE;

                    SET _counter := _counter + 1;

                END WHILE; 
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
        DB::unprepared('DROP PROCEDURE UpdateNestingLevelInAlbumsOrFolderData');
    }
}
