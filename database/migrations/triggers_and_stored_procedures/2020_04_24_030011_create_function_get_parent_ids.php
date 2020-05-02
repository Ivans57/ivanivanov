<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionGetParentIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION GetParentIDs(_table_name VARCHAR(45), _parent_id INT)
        RETURNS JSON
            BEGIN		
		CASE _table_name
                    WHEN "en_albums" THEN SET @parents := (SELECT parents from en_albums_data WHERE items_id = _parent_id);
                    WHEN "ru_albums" THEN SET @parents := (SELECT parents from ru_albums_data WHERE items_id = _parent_id);
                    WHEN "en_folders" THEN SET @parents := (SELECT parents from en_folders_data WHERE items_id = _parent_id);
                    WHEN "ru_folders" THEN SET @parents := (SELECT parents from ru_folders_data WHERE items_id = _parent_id);
		END CASE;
			
		IF (@parents IS NULL) THEN
                    SET @parents := JSON_ARRAY();
		END IF;
	
		#We have to keep all ids in JSON arrays as string values, not int,
                #as database strandard functions might have some problems
                #with JSON arrays containing int values, e.g. impossible to find any int value
                #using JSON_SEARCH function.
		SET @parents := JSON_ARRAY_INSERT(@parents, "$[0]", CONVERT(_parent_id, CHAR));
		RETURN @parents;
            END;
        ');
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION GetParentIDs');
    }
}
