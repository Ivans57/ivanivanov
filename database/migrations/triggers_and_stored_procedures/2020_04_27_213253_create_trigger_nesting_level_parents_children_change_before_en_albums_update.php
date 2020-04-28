<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNestingLevelParentsChildrenChangeBeforeEnAlbumsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `nesting_level_parents_children_change_before_en_albums_update` BEFORE UPDATE ON `en_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		IF ((NEW.included_in_album_with_id <=> OLD.included_in_album_with_id) = 0) THEN
		
                    SET @items_children := (SELECT children FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = OLD.id);
			
                    IF (@items_children IS NULL) THEN
			IF (NEW.included_in_album_with_id IS NULL) THEN				
                            SET @new_nesting_level := 0;				
                        ELSE				
                            SET @new_nesting_level := (SELECT nesting_level from en_albums_ids_nesting_levels_parents_children where items_id = NEW.included_in_album_with_id) + 1;				
                        END IF;
								
                        UPDATE en_albums_ids_nesting_levels_parents_children SET nesting_level = @new_nesting_level WHERE items_id = OLD.id;
                    ELSE
			#Here we need to include being moved items children ids and its id.
			SET @items_children := JSON_ARRAY_INSERT(@items_children, "$[0]", CONVERT(OLD.id, CHAR));
			SET @old_nesting_level := (SELECT nesting_level from en_albums_ids_nesting_levels_parents_children where items_id = OLD.id);
				
			WHILE (_counter < JSON_LENGTH(@items_children)) DO
				
                            SET @current_items_id := CONVERT(JSON_EXTRACT(@items_children, CONCAT("$[",_counter,"]")), UNSIGNED);
										
                            SET @nesting_level_relating_to_root_parent = 
				(SELECT nesting_level from en_albums_ids_nesting_levels_parents_children where items_id = @current_items_id) - @old_nesting_level;
					
                            IF (NEW.included_in_album_with_id IS NULL) THEN				
				SET @new_nesting_level := @nesting_level_relating_to_root_parent;				
                            ELSE				
				#We need to add 1 because being moved item will be one level down then its closest parent
				SET @new_nesting_level := (SELECT nesting_level from en_albums_ids_nesting_levels_parents_children WHERE items_id = NEW.included_in_album_with_id) + 1 + @nesting_level_relating_to_root_parent;				
                            END IF;
												
                            UPDATE en_albums_ids_nesting_levels_parents_children SET nesting_level = @new_nesting_level WHERE items_id = @current_items_id;
                
                            SET _counter := _counter + 1;
    
			END WHILE;			
                    END IF;       
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
        DB::unprepared('DROP TRIGGER `nesting_level_parents_children_change_before_en_albums_update`');
    }
}
