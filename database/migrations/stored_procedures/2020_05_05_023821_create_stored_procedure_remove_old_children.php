<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureRemoveOldChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE RemoveOldChildren(IN _children JSON, IN _old_parent_ids JSON, IN _table_name VARCHAR(45), IN _field_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
				
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
        DB::unprepared('DROP PROCEDURE RemoveOldChildren');
    }
}
