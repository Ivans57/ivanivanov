<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureGetNewParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE GetNewParents(IN _items_id INT, IN _new_parents_id INT, IN  _children_without_current_item JSON, IN _children JSON, IN _parents_to_remove JSON, IN _new_parents_without_prev JSON, IN _table_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
		
                #We are going to use a temporary table, as there are some diffuclties with values extraction from a json array 
                #which was previously extracted from another json array.
                #This table will be dropped in procedure UpdateParentsInAlbumsOrFolderData
                CREATE TEMPORARY TABLE parents(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, parents JSON);

		IF (_children_without_current_item IS NULL) THEN
                    IF (_new_parents_id IS NULL) THEN				
			SET @new_parents := NULL;
                    ELSE
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				SET @new_parents := (SELECT parents FROM en_albums_data WHERE items_id = _new_parents_id);
                            WHEN "ru_albums_data" THEN 
				SET @new_parents := (SELECT parents FROM ru_albums_data WHERE items_id = _new_parents_id);
                            WHEN "en_folders_data" THEN 
				SET @new_parents := (SELECT parents FROM en_folders_data WHERE items_id = _new_parents_id);
                            WHEN "ru_folders_data" THEN 
				SET @new_parents := (SELECT parents FROM ru_folders_data WHERE items_id = _new_parents_id);
			END CASE;

			IF (@new_parents IS NULL) THEN
                            SET @new_parents := JSON_ARRAY();
			END IF;
			SET @new_parents := JSON_ARRAY_INSERT(@new_parents, "$[0]", CONVERT(_new_parents_id, CHAR));    
                    END IF;
                    INSERT INTO parents (parents) VALUES (@new_parents);
		ELSE
                    WHILE (_counter < JSON_LENGTH(_children)) DO

                        SET @current_items_id := CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);

                        #First of all we need to remove no need anymore parent ids from childrens parents array
                        #But before we need to to the following check, because if an element does not have parents JSON_MERGE will not work properly.
                        #Also we need to do this check only for the root parent of the array of its kids, because its kids will have at least one parent to keep.
                        IF (_parents_to_remove IS NULL AND @current_items_id = _items_id) THEN
                            SET @parent_ids_without_old_parents := JSON_ARRAY();
                        ELSE
                            SET @parent_ids_without_old_parents := RemoveItemFromJSON(@current_items_id, _parents_to_remove, _table_name, "parents");
                        END IF;
                        IF (_new_parents_without_prev IS NULL) THEN
                            SET _new_parents_without_prev := JSON_ARRAY();
                        END IF;
                        #Now we need to merge two arrays one @parent_ids_without_old_parents and _new_parents_without_prev
                        SET @new_parents := JSON_MERGE(@parent_ids_without_old_parents, _new_parents_without_prev);
                        IF (JSON_LENGTH(@new_parents) < 1) THEN
                            SET @new_parents := NULL;
                        END IF;

                        INSERT INTO parents (parents) VALUES (@new_parents);

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
        DB::unprepared('DROP PROCEDURE GetNewParents');
    }
}
