<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionGetNewNestingLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION GetNewNestingLevel(_items_id INT, _new_parents_id INT, _children_without_current_item JSON, _children JSON, _table_name VARCHAR(45))
        RETURNS JSON
            BEGIN
		DECLARE _counter INT DEFAULT 0;
			
		SET @new_nesting_level_array := JSON_ARRAY();
		
		IF (_children_without_current_item IS NULL) THEN
                    IF (_new_parents_id IS NULL) THEN				
			SET @new_nesting_level := 0;
                    ELSE
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				SET @new_nesting_level := (SELECT nesting_level FROM en_albums_data WHERE items_id = _new_parents_id) + 1;
                            WHEN "ru_albums_data" THEN 
				SET @new_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = _new_parents_id) + 1;
                            WHEN "en_folders_data" THEN 
				SET @new_nesting_level := (SELECT nesting_level FROM en_folders_data WHERE items_id = _new_parents_id) + 1;
                            WHEN "ru_folders_data" THEN 
				SET @new_nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = _new_parents_id) + 1;
                        END CASE;
                    END IF;
			
                    SET @new_nesting_level_array := JSON_ARRAY_INSERT(@new_nesting_level_array, "$[0]", @new_nesting_level);
			
		ELSE
			
                    CASE _table_name
			WHEN "en_albums_data" THEN 
                            SET @old_nesting_level := (SELECT nesting_level FROM en_albums_data WHERE items_id = _items_id);
			WHEN "ru_albums_data" THEN 
                            SET @old_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = _items_id);
			WHEN "en_folders_data" THEN 
                            SET @old_nesting_level := (SELECT nesting_level FROM en_folders_data WHERE items_id = _items_id);
			WHEN "ru_folders_data" THEN 
                            SET @old_nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = _items_id);
                    END CASE;

                    WHILE (_counter < JSON_LENGTH(_children)) DO
					
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
							(SELECT nesting_level FROM en_albums_data WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED)) - @old_nesting_level;
                            WHEN "ru_albums_data" THEN 
                                SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM ru_albums_data WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED)) - @old_nesting_level;
                            WHEN "en_folders_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM en_folders_data WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED)) - @old_nesting_level;
                            WHEN "ru_folders_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM ru_folders_data WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED)) - @old_nesting_level;
                        END CASE;

                        IF (_new_parents_id IS NULL) THEN				
                            SET @new_nesting_level := @nesting_level_relating_to_root_parent;				
                        ELSE				
                            #We need to add 1 because being moved item will be one level down then its closest parent
                            CASE _table_name
                                WHEN "en_albums_data" THEN 
                                    SET @new_nesting_level := (SELECT nesting_level FROM en_albums_data WHERE items_id = _new_parents_id) + 1 + @nesting_level_relating_to_root_parent;
                                WHEN "ru_albums_data" THEN 
                                    SET @new_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = _new_parents_id) + 1 + @nesting_level_relating_to_root_parent;
                                WHEN "en_folders_data" THEN 
                                    SET @new_nesting_level := (SELECT nesting_level FROM en_folders_data WHERE items_id = _new_parents_id) + 1 + @nesting_level_relating_to_root_parent;
                                WHEN "ru_folders_data" THEN 
                                    SET @new_nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = _new_parents_id) + 1 + @nesting_level_relating_to_root_parent;
                            END CASE;					
                        END IF;

                        SET @new_nesting_level_array := JSON_ARRAY_INSERT(@new_nesting_level_array, CONCAT("$[",_counter,"]"), @new_nesting_level);

                        SET _counter := _counter + 1;

                    END WHILE;			
		END IF; 

                RETURN @new_nesting_level_array;
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
        DB::unprepared('DROP FUNCTION GetNewNestingLevel');
    }
}
