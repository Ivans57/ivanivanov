<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerNestingLevelParentsChildrenSetAfterEnAlbumsInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `nesting_level_parents_children_set_after_en_albums_insert` AFTER INSERT ON `en_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;	
		
		IF (NEW.included_in_album_with_id IS NULL) THEN
                    INSERT INTO en_albums_ids_nesting_levels_parents_children (items_id, nesting_level, parents, children) VALUES (NEW.id, 0, NULL, NULL);
		ELSE
                    SET @nesting_level := (SELECT nesting_level from en_albums_ids_nesting_levels_parents_children WHERE items_id = NEW.included_in_album_with_id) + 1;			
                    SET @parents := GetParentIDs("en_albums", NEW.included_in_album_with_id);
			
                    WHILE (_counter < JSON_LENGTH(@parents)) DO
			
                        #We have to keep all ids in JSON arrays as string values, not int,
                        #as database strandard functions might have some problems
                        #with JSON arrays containing int values, e.g. impossible to find any int value
                        #using JSON_SEARCH function.
                        SET @one_parent_id := CONVERT(JSON_EXTRACT(@parents, CONCAT("$[",_counter,"]")), UNSIGNED);
				
                        SET @children := GetChildrenIDs("en_albums", @one_parent_id, NEW.id);
					
                        UPDATE en_albums_ids_nesting_levels_parents_children SET children = @children WHERE items_id = @one_parent_id;
                
                        SET _counter := _counter + 1;
    
                    END WHILE; 
                    
                INSERT INTO en_albums_ids_nesting_levels_parents_children (items_id, nesting_level, parents, children) VALUES (NEW.id, @nesting_level, @parents, NULL);
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
        DB::unprepared('DROP TRIGGER `nesting_level_parents_children_set_after_en_albums_insert`');
    }
}
