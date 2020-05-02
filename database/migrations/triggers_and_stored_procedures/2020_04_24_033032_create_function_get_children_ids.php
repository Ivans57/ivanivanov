<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionGetChildrenIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION GetChildrenIDs(_table_name VARCHAR(45), _parent_id INT, _items_id INT)
        RETURNS JSON
            BEGIN		
                CASE _table_name
                    WHEN "en_albums" THEN SET @children := (SELECT children FROM en_albums_data WHERE items_id = _parent_id);
                    WHEN "ru_albums" THEN SET @children := (SELECT children FROM ru_albums_data WHERE items_id = _parent_id);
                    WHEN "en_folders" THEN SET @children := (SELECT children FROM en_folders_data WHERE items_id = _parent_id);
                    WHEN "ru_folders" THEN SET @children := (SELECT children FROM ru_folders_data WHERE items_id = _parent_id);
		END CASE;
					
		IF (@children IS NULL) THEN
                    SET @children := JSON_ARRAY();
		END IF;
		
		#We have to keep all ids in JSON arrays as string values, not int,
                #as database strandard functions might have some problems
                #with JSON arrays containing int values, e.g. impossible to find any int value
                #using JSON_SEARCH function.
		SET @children := JSON_ARRAY_INSERT(@children, "$[0]", CONVERT(_items_id, CHAR));
		RETURN @children;
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
        DB::unprepared('DROP FUNCTION GetChildrenIDs');
    }
}
