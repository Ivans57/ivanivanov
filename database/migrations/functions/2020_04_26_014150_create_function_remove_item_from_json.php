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
        CREATE FUNCTION RemoveItemFromJSON(_parent_id INT, _items_ids_to_remove JSON, _table_name VARCHAR(45), _field_name VARCHAR(45))
        RETURNS JSON
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		SET @items := GetParentsOrChildren(_parent_id, _table_name, _field_name);
        
		IF (_items_ids_to_remove IS NOT NULL) THEN
		
                    WHILE (_counter < JSON_LENGTH(_items_ids_to_remove)) DO
					
			SET @items := JSON_REMOVE(@items, replace(JSON_SEARCH(@items, *all*, 
                            replace(JSON_EXTRACT(_items_ids_to_remove, CONCAT("$[",_counter,"]")), *"*, **)), *"*, **));
					
			SET _counter := _counter + 1;
		
                    END WHILE;
					
                    #Below we need a check as items array might have only one value and after its removal the array is becoming empty,
                    #but we do not keep empty arrays in children fields. For parent fields we do the same, but later on we need to merge
                    #this result with another array. We cannot merge with null, thats why we still need an empty array.
                    IF (JSON_LENGTH(@items) = 0 AND _field_name = "children") THEN
			SET @items := NULL;           
                    END IF;

		END IF;

                RETURN @items;
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
