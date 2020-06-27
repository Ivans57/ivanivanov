<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerInsertIntoRuFoldersDataTableAfterRuFoldersInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `insert_into_ru_folders_data_table_after_ru_folders_insert` AFTER INSERT ON `ru_folders` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;	
		
		IF (NEW.included_in_folder_with_id IS NULL) THEN
                    INSERT INTO ru_folders_data (items_id, nesting_level, parents, children) VALUES (NEW.id, 0, NULL, NULL);
		ELSE
                    SET @nesting_level := (SELECT nesting_level FROM ru_folders_data WHERE items_id = NEW.included_in_folder_with_id) + 1;			
                    SET @parents := GetParentIDs("ru_folders", NEW.included_in_folder_with_id);

                    WHILE (_counter < JSON_LENGTH(@parents)) DO

                        #We have to keep all ids in JSON arrays as string values, not int,
                        #as database strandard functions might have some problems
                        #with JSON arrays containing int values, e.g. impossible to find any int value
                        #using JSON_SEARCH function.
                        SET @one_parent_id := CONVERT(JSON_EXTRACT(@parents, CONCAT("$[",_counter,"]")), UNSIGNED);

                        SET @children := GetChildrenIDs("ru_folders", @one_parent_id, NEW.id);

                        UPDATE ru_folders_data SET children = @children WHERE items_id = @one_parent_id;

                        SET _counter := _counter + 1;

                    END WHILE; 

                    INSERT INTO ru_folders_data (items_id, nesting_level, parents, children) VALUES (NEW.id, @nesting_level, @parents, NULL);
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
        DB::unprepared('DROP TRIGGER `insert_into_ru_folders_data_table_after_ru_folders_insert`');
    }
}