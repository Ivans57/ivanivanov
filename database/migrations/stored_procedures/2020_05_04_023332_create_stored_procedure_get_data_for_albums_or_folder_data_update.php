<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureGetDataForAlbumsOrFolderDataUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE GetDataForAlbumsOrFolderDataUpdate(IN _items_id INT, IN _new_parents_id INT, IN _table_name VARCHAR(45), 
                                                            OUT _items_children JSON, OUT _items_children_with_current_item JSON, 
                                                            OUT _items_parents_to_remove JSON, OUT _new_parents_without_prev JSON)
            BEGIN
		CASE _table_name
                    WHEN "en_albums_data" THEN 
			SET _items_children := (SELECT children FROM en_albums_data WHERE items_id = _items_id);
                    WHEN "ru_albums_data" THEN 
			SET _items_children := (SELECT children FROM ru_albums_data WHERE items_id = _items_id);
                    WHEN "en_folders_data" THEN 
			SET _items_children := (SELECT children FROM en_folders_data WHERE items_id = _items_id);
                    WHEN "ru_folders_data" THEN 
			SET _items_children := (SELECT children FROM ru_folders_data WHERE items_id = _items_id);
		END CASE;

		CASE _table_name
                    WHEN "en_albums_data" THEN 
			SET _items_parents_to_remove := (SELECT parents FROM en_albums_data WHERE items_id = _items_id);
                    WHEN "ru_albums_data" THEN 
			SET _items_parents_to_remove := (SELECT parents FROM ru_albums_data WHERE items_id = _items_id);
                    WHEN "en_folders_data" THEN 
			SET _items_parents_to_remove := (SELECT parents FROM en_folders_data WHERE items_id = _items_id);
                    WHEN "ru_folders_data" THEN 
			SET _items_parents_to_remove := (SELECT parents FROM ru_folders_data WHERE items_id = _items_id);
		END CASE;
		
		CASE _table_name
                    WHEN "en_albums_data" THEN 
                        SET _new_parents_without_prev := (SELECT parents FROM en_albums_data WHERE items_id = _new_parents_id);
                    WHEN "ru_albums_data" THEN 
			SET _new_parents_without_prev := (SELECT parents FROM ru_albums_data WHERE items_id = _new_parents_id);
                    WHEN "en_folders_data" THEN 
			SET _new_parents_without_prev := (SELECT parents FROM en_folders_data WHERE items_id = _new_parents_id);
                    WHEN "ru_folders_data" THEN 
			SET _new_parents_without_prev := (SELECT parents FROM ru_folders_data WHERE items_id = _new_parents_id);
		END CASE;

                IF (_new_parents_without_prev IS NULL) THEN	
                    #We need to make an empty array as we cannot operate with NULL using JSON_MERGE later.
                    SET _new_parents_without_prev := JSON_ARRAY();
                END IF;
                IF (_new_parents_id IS NOT NULL) THEN
                    SET _new_parents_without_prev := JSON_ARRAY_INSERT(_new_parents_without_prev, "$[0]", CONVERT(_new_parents_id, CHAR));
                END IF;

                SET @items_id_in_array := JSON_ARRAY();
                SET @items_id_in_array := JSON_ARRAY_INSERT(@items_id_in_array, "$[0]", CONVERT(_items_id, CHAR));			
                IF (_items_children IS NULL) THEN
                    SET _items_children_with_current_item := @items_id_in_array;
		ELSE
                    SET _items_children_with_current_item := JSON_MERGE(_items_children, @items_id_in_array);
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
        DB::unprepared('DROP PROCEDURE GetDataForAlbumsOrFolderDataUpdate');
    }
}
