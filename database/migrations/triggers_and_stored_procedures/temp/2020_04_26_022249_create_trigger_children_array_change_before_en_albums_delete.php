<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerChildrenArrayChangeBeforeEnAlbumsDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */   
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `children_array_change_before_en_albums_delete` BEFORE DELETE ON `en_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		SET @parents := (SELECT parents FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = OLD.id);
		
		SET @items_ids_to_remove := (SELECT children FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = OLD.id);
		IF (@items_ids_to_remove IS NULL) THEN
                    SET @items_ids_to_remove := JSON_ARRAY();
		END IF;
		SET @items_ids_to_remove := JSON_ARRAY_INSERT(@items_ids_to_remove, "$[0]", CONVERT(OLD.id, CHAR));
		
		WHILE (_counter < JSON_LENGTH(@parents)) DO
				
                    SET @one_parent_id := CONVERT(JSON_EXTRACT(@parents, CONCAT("$[",_counter,"]")), UNSIGNED);		
			
                    UPDATE en_albums_ids_nesting_levels_parents_children SET children = 
                        RemoveItemFromJSON(@one_parent_id, @items_ids_to_remove, "en_albums_ids_nesting_levels_parents_children", "children") 
			WHERE items_id = @one_parent_id;
                
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
        DB::unprepared('DROP TRIGGER `children_array_change_before_en_albums_delete`');
    }
}
