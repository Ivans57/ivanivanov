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
    //
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `nesting_level_parents_children_change_before_en_albums_update` BEFORE UPDATE ON `en_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		IF ((NEW.included_in_album_with_id <=> OLD.included_in_album_with_id) = 0) THEN
		
                    SET @items_children := (SELECT children FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = OLD.id);
			
                    SET @items_parents_to_remove := (SELECT parents FROM en_albums_ids_nesting_levels_parents_children WHERE items_id = OLD.id);
                    SET @new_parents_without_prev := (SELECT parents from en_albums_ids_nesting_levels_parents_children where items_id = NEW.included_in_album_with_id);
			
                    IF (@new_parents_without_prev IS NULL) THEN	
			#We need to make an empty array as we cannot operate with NULL using JSON_MERGE later.
			SET @new_parents_without_prev := JSON_ARRAY();
                    END IF;
                    IF (NEW.included_in_album_with_id IS NOT NULL) THEN
			SET @new_parents_without_prev := JSON_ARRAY_INSERT(@new_parents_without_prev, "$[0]", CONVERT(NEW.included_in_album_with_id, CHAR));
                    END IF;
			
                    IF (@items_children IS NULL) THEN
			IF (NEW.included_in_album_with_id IS NULL) THEN				
                            SET @new_nesting_level := 0;
                            SET @new_parents := NULL;
			ELSE				
                            SET @new_nesting_level := (SELECT nesting_level from en_albums_ids_nesting_levels_parents_children where items_id = NEW.included_in_album_with_id) + 1;
                            SET @new_parents := (SELECT parents from en_albums_ids_nesting_levels_parents_children where items_id = NEW.included_in_album_with_id);
					
                            IF (@new_parents IS NULL) THEN
				SET @new_parents := JSON_ARRAY();
                            END IF;
                            SET @new_parents := JSON_ARRAY_INSERT(@new_parents, "$[0]", CONVERT(NEW.included_in_album_with_id, CHAR));
			END IF;
			
                        #We need to assign this variable, because we are going to use its value in UpdateChildrenInEnAlbumsIdsNestingLevelsParentsChildren
			#I cannot do it earlier out of IF, because otherwise my IF would not work. It should have NULL initially.
                        #Possibly, I might need to revise the whole IF logic of this trigger.
			SET @items_children := JSON_ARRAY();
			SET @items_children := JSON_ARRAY_INSERT(@items_children, "$[0]", CONVERT(OLD.id, CHAR));
			UPDATE en_albums_ids_nesting_levels_parents_children SET nesting_level = @new_nesting_level, parents = @new_parents WHERE items_id = OLD.id;
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
					
                            #First of all we need to remove no need anymore parent ids from childrens parents array
                            #But before we need to to the following check, because if an element does not have parents JSON_MERGE will not work properly.
                            #Also we need to do this check only for the root parent of the array of its kids, because its kids will have at least one parent to keep.
                            IF (@items_parents_to_remove IS NULL AND @current_items_id = OLD.id) THEN
				SET @parent_ids_without_old_parents := JSON_ARRAY();
                            ELSE
				SET @parent_ids_without_old_parents := RemoveItemFromJSON(@current_items_id, @items_parents_to_remove, "en_albums_ids_nesting_levels_parents_children", "parents");
                            END IF;
                            #Now we need to merge two arrays one @parent_ids_without_old_parents and @new_parents_without_prev
                            SET @new_parents := JSON_MERGE(@new_parents_without_prev, @parent_ids_without_old_parents);
					
                            #We do not keep empty arrays in the database table. If we get one, NULL needs to be assigned.
                            IF (JSON_LENGTH(@new_parents) < 1) THEN
				SET @new_parents := NULL;
                            END IF;
					
                            UPDATE en_albums_ids_nesting_levels_parents_children SET nesting_level = @new_nesting_level, parents = @new_parents WHERE items_id = @current_items_id;
                
                            SET _counter := _counter + 1;
    
			END WHILE;			
                    END IF; 
                    CALL UpdateChildrenInEnAlbumsIdsNestingLevelsParentsChildren(@items_children, @items_parents_to_remove, @new_parents_without_prev, "en_albums_ids_nesting_levels_parents_children", "children");
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
