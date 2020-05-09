<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureInsertNewChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE InsertNewChildren(IN _children JSON, IN _new_parent_ids JSON, IN _table_name VARCHAR(45), IN _field_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		WHILE (_counter < JSON_LENGTH(_new_parent_ids)) DO
						
                    SET @current_parents_id := CONVERT(JSON_EXTRACT(_new_parent_ids, CONCAT("$[",_counter,"]")), UNSIGNED);

                    SET @new_parents_children := GetParentsOrChildren(@current_parents_id, _table_name, _field_name); 

                    #Maybe we do not need it as if JSON_MERGE has null as one argument, it can give null as result. Need to cehck it.
                    IF(@new_parents_children IS NULL) THEN
                        SET @new_parents_children := JSON_ARRAY();
                    END IF;

                    SET @new_children := JSON_MERGE(_children, @new_parents_children);
							
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
        DB::unprepared('DROP PROCEDURE InsertNewChildren');
    }
}
