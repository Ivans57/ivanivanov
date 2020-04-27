<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionRemoveItemFromJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        //As there are some issues with passing quoted text direcltly to DB::unprepared statement,
        //first we need to make MySql query with * instead of ' and then we need to
        //replace * back to '.
        $RemoveItemCode = '
        CREATE FUNCTION RemoveItemFromJSON(_parent_id INT, _items_ids_to_remove JSON, _table_name VARCHAR(45))
        RETURNS JSON
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		CASE _table_name
                    WHEN "en_albums_ids_nesting_levels_parents_children" THEN 
                            SET @children := (SELECT children FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = _parent_id);
                    WHEN "ru_albums_ids_nesting_levels_parents_children" THEN 
                            SET @children := (SELECT children FROM ru_albums_ids_nesting_levels_parents_children WHERE items_id = _parent_id);
                    WHEN "en_folders_ids_nesting_levels_parents_children" THEN 
                            SET @children := (SELECT children FROM en_folders_ids_nesting_levels_parents_children WHERE items_id = _parent_id);
                    WHEN "ru_folders_ids_nesting_levels_parents_children" THEN 
                            SET @children := (SELECT children FROM ru_folders_ids_nesting_levels_parents_children WHERE items_id = _parent_id);
		END CASE;				
         		 
		WHILE (_counter < JSON_LENGTH(_items_ids_to_remove)) DO
				
			SET @children := JSON_REMOVE(@children, replace(JSON_SEARCH(@children, *all*, 
                            replace(JSON_EXTRACT(_items_ids_to_remove, CONCAT("$[",_counter,"]")), *"*, **)), *"*, **));
                
                SET _counter := _counter + 1;
    
                END WHILE;
				
                #Below we need a check as children array might have only one value and after its removal the array is becoming empty,
                #but we do not keep empty arrays in children field.
                IF (JSON_LENGTH(@children) = 0) THEN
                    SET @children := NULL;           
                END IF;

                RETURN @children;
            END
        ';
        $RemoveItem = str_replace("*","'", $RemoveItemCode);
        DB::unprepared($RemoveItem);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION RemoveItemFromJSON');
    }
}
