<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureUpdateParentsInAlbumsOrFolderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE UpdateParentsInAlbumsOrFolderData(IN _items_id INT, IN _new_parents_id INT, IN _children_without_current_item JSON, IN _children JSON, IN _parents_to_remove JSON, IN _new_parents_without_prev JSON, IN _table_name VARCHAR(45))
            BEGIN
		DECLARE _counter INT DEFAULT 0;
		
                #The task of this procedure is to make a temporary table and fill it with necessary data.
		CALL GetNewParents(_items_id, _new_parents_id, _children_without_current_item, _children, _parents_to_remove, _new_parents_without_prev, _table_name);

                WHILE (_counter < JSON_LENGTH(_children)) DO
                
                    CASE _table_name
                        WHEN "en_albums_data" THEN 
                            UPDATE en_albums_data SET parents = (SELECT parents FROM parents WHERE id = (_counter + 1)) WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
                        WHEN "ru_albums_data" THEN 
                            UPDATE ru_albums_data SET parents = (SELECT parents FROM parents WHERE id = (_counter + 1)) WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
                        WHEN "en_folders_data" THEN 
                            UPDATE en_folders_data SET parents = (SELECT parents FROM parents WHERE id = (_counter + 1)) WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
                        WHEN "ru_folders_data" THEN 
                            UPDATE ru_folders_data SET parents = (SELECT parents FROM parents WHERE id = (_counter + 1)) WHERE items_id = CONVERT(JSON_EXTRACT(_children, CONCAT("$[",_counter,"]")), UNSIGNED);
                    END CASE;

                    SET _counter := _counter + 1;

                END WHILE;
                #The following temporary table was created in GetNewParents procedure.
                DROP TEMPORARY TABLE parents;
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
        DB::unprepared('DROP PROCEDURE UpdateParentsInAlbumsOrFolderData');
    }
}
