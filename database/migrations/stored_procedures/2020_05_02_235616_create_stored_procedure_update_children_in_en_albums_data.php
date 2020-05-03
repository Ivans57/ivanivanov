<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureUpdateChildrenInEnAlbumsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE UpdateChildrenInEnAlbumsData(IN _children JSON, IN _old_parent_ids JSON, IN _new_parent_ids JSON, IN _table_name VARCHAR(45), IN _field_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
				
		IF (_old_parent_ids IS NOT NULL) THEN

                    WHILE (_counter < JSON_LENGTH(_old_parent_ids)) DO
						
			SET @current_parents_id := CONVERT(JSON_EXTRACT(_old_parent_ids, CONCAT("$[",_counter,"]")), UNSIGNED);
					
			SET @new_children := RemoveItemFromJSON(@current_parents_id, _children, _table_name, _field_name);
						
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				UPDATE en_albums_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "ru_albums_data" THEN 
				UPDATE ru_albums_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "en_folders_data" THEN 
				UPDATE en_folders_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "ru_folders_data" THEN 
				UPDATE ru_folders_data SET children = @new_children WHERE items_id = @current_parents_id;
			END CASE;
						
			SET _counter := _counter + 1;
		
                    END WHILE;	

		END IF;
				
		#After first loop we need to zero counter as we are going to use it again.
		SET _counter := 0;
				
		IF (_new_parent_ids IS NOT NULL) THEN
													
                    WHILE (_counter < JSON_LENGTH(_new_parent_ids)) DO
						
			SET @current_parents_id := CONVERT(JSON_EXTRACT(_new_parent_ids, CONCAT("$[",_counter,"]")), UNSIGNED);
						
			SET @new_parents_children := GetParentsOrChildren(@current_parents_id, _table_name, _field_name); 
					
			#Maybe we do not need it as if JSON_MERGE has null as one argument, it can give null as result. Need to cehck it.
			IF(@new_parents_children IS NULL) THEN
                            SET @new_parents_children := JSON_ARRAY();
			END IF;
					
			SET @new_children := JSON_MERGE(@new_parents_children, _children);
					
			#We do not keep empty arrays in the database table. If we get one, NULL needs to be assigned.
			IF (JSON_LENGTH(@new_children) < 1) THEN
                            SET @new_children := NULL;
			END IF;
						
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				UPDATE en_albums_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "ru_albums_data" THEN 
				UPDATE ru_albums_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "en_folders_data" THEN 
				UPDATE en_folders_data SET children = @new_children WHERE items_id = @current_parents_id;
                            WHEN "ru_folders_data" THEN 
				UPDATE ru_folders_data SET children = @new_children WHERE items_id = @current_parents_id;
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
        DB::unprepared('DROP PROCEDURE UpdateChildrenInEnAlbumsData');
    }
}