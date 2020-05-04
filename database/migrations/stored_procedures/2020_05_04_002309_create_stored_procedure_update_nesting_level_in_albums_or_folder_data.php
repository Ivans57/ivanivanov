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
			
                    CASE _table_name
			WHEN "en_albums_data" THEN 
                            UPDATE en_albums_data SET nesting_level = @new_nesting_level WHERE items_id = _items_id;
			WHEN "ru_albums_data" THEN 
                            UPDATE ru_albums_data SET nesting_level = @new_nesting_level WHERE items_id = _items_id;
			WHEN "en_folders_data" THEN 
                            UPDATE en_folders_data SET nesting_level = @new_nesting_level WHERE items_id = _items_id;
			WHEN "ru_folders_data" THEN 
                            UPDATE ru_folders_data SET nesting_level = @new_nesting_level WHERE items_id = _items_id;
                    END CASE;
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

                        SET @current_items_id := CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
				
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM en_albums_data WHERE items_id = @current_items_id) - @old_nesting_level;
                            WHEN "ru_albums_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM ru_albums_data WHERE items_id = @current_items_id) - @old_nesting_level;
                            WHEN "en_folders_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM en_folders_data WHERE items_id = @current_items_id) - @old_nesting_level;
                            WHEN "ru_folders_data" THEN 
				SET @nesting_level_relating_to_root_parent := 
                                    (SELECT nesting_level FROM ru_folders_data WHERE items_id = @current_items_id) - @old_nesting_level;
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

			CASE _table_name
                            WHEN "en_albums_data" THEN 
				UPDATE en_albums_data SET nesting_level = @new_nesting_level WHERE items_id = @current_items_id;
                            WHEN "ru_albums_data" THEN 
				UPDATE ru_albums_data SET nesting_level = @new_nesting_level WHERE items_id = @current_items_id;
                            WHEN "en_folders_data" THEN 
				UPDATE en_folders_data SET nesting_level = @new_nesting_level WHERE items_id = @current_items_id;
                            WHEN "ru_folders_data" THEN 
				UPDATE ru_folders_data SET nesting_level = @new_nesting_level WHERE items_id = @current_items_id;
			END CASE;

                        SET _counter := _counter + 1;

                    END WHILE;			
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
        DB::unprepared('DROP PROCEDURE UpdateNestingLevelInAlbumsOrFolderData');
    }
}
