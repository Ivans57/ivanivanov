<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerChildrenArrayChangeBeforeRuAlbumsDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `children_array_change_before_ru_albums_delete` BEFORE DELETE ON `ru_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		SET @parents := (SELECT parents FROM ru_albums_data WHERE items_id = OLD.id);
		
		SET @items_ids_to_remove := (SELECT children FROM ru_albums_data WHERE items_id = OLD.id);
		IF (@items_ids_to_remove IS NULL) THEN
                    SET @items_ids_to_remove := JSON_ARRAY();
		END IF;
		SET @items_ids_to_remove := JSON_ARRAY_INSERT(@items_ids_to_remove, "$[0]", CONVERT(OLD.id, CHAR));
		
		WHILE (_counter < JSON_LENGTH(@parents)) DO
				
                    SET @one_parent_id := CONVERT(JSON_EXTRACT(@parents, CONCAT("$[",_counter,"]")), UNSIGNED);		
			
                    UPDATE ru_albums_data SET children = RemoveItemFromJSON(@one_parent_id, @items_ids_to_remove, "ru_albums_data", "children") 
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
        DB::unprepared('DROP TRIGGER `children_array_change_before_ru_albums_delete`');
    }
}
