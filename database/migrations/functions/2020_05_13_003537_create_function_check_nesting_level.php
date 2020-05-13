<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionCheckNestingLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION CheckNestingLevel(_items_id INT, _potential_parents_nesting_level INT, _table_name VARCHAR(45))
        RETURNS BOOL
            BEGIN
		DECLARE _counter INT DEFAULT 0;		
		DECLARE _result BOOL DEFAULT TRUE;
	
	
		CALL GetNestingLevelsAndChildren(_items_id, _table_name, @items_nesting_level, @items_children);
	
		#We are subtracting from 6, because any item on level 7 cannot accept any item inside.
		SET @max_rel_acceptable_nesting_level := 6 - _potential_parents_nesting_level;
		
		IF (@max_rel_acceptable_nesting_level > 0 AND @items_children IS NOT NULL) THEN
		
                    childrenloop: WHILE (_counter < JSON_LENGTH(@items_children)) DO
					
			CASE _table_name
                            WHEN "en_albums_data" THEN 
				SET @childs_nesting_level := (SELECT nesting_level FROM en_albums_data WHERE items_id = CONVERT(JSON_EXTRACT(@items_children, CONCAT("$[",_counter,"]")), UNSIGNED));
                            WHEN "ru_albums_data" THEN 
				SET @childs_nesting_level := (SELECT nesting_level FROM ru_albums_data WHERE items_id = CONVERT(JSON_EXTRACT(@items_children, CONCAT("$[",_counter,"]")), UNSIGNED));
                            WHEN "en_folders_data" THEN 
				SET @childs_nesting_level := (SELECT nesting_level FROM en_folders_data WHERE items_id = CONVERT(JSON_EXTRACT(@items_children, CONCAT("$[",_counter,"]")), UNSIGNED));
                            WHEN "ru_folders_data" THEN 
				SET @childs_nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = CONVERT(JSON_EXTRACT(@items_children, CONCAT("$[",_counter,"]")), UNSIGNED));
			END CASE;
				
			#We are interested in childs nesting level relating to its parent.
			IF ((@childs_nesting_level - @items_nesting_level) > @max_rel_acceptable_nesting_level) THEN					
                            SET _result := FALSE;					
                            LEAVE childrenloop;				
			END IF;

			SET _counter = _counter + 1;
			
                    END WHILE childrenloop;
				
		ELSEIF (@max_rel_acceptable_nesting_level < 1 AND @items_children IS NOT NULL) THEN

                    SET _result := FALSE;
				
		END IF;

                RETURN _result;
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
        DB::unprepared('DROP FUNCTION CheckNestingLevel');
    }
}
