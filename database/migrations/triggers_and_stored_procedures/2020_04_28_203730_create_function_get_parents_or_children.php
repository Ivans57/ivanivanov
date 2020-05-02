<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionGetParentsOrChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION GetParentsOrChildren(_items_id INT, _table_name VARCHAR(45), _field_name VARCHAR(45))
        RETURNS JSON
            BEGIN
		IF (_field_name = "children") THEN
                    CASE _table_name
			WHEN "en_albums_data" THEN 
                            SET @items := (SELECT children FROM en_albums_data WHERE items_id = _items_id);
			WHEN "ru_albums_data" THEN 
                            SET @items := (SELECT children FROM ru_albums_data WHERE items_id = _items_id);
			WHEN "en_folders_data" THEN 
                            SET @items := (SELECT children FROM en_folders_data WHERE items_id = _items_id);
			WHEN "ru_folders_data" THEN 
                            SET @items := (SELECT children FROM ru_folders_data WHERE items_id = _items_id);
                    END CASE;				
                ELSEIF (_field_name = "parents") THEN
                    CASE _table_name
                        WHEN "en_albums_data" THEN 
                            SET @items := (SELECT parents FROM en_albums_data WHERE items_id = _items_id);
                        WHEN "ru_albums_data" THEN 
                            SET @items := (SELECT parents FROM ru_albums_data WHERE items_id = _items_id);
                        WHEN "en_folders_data" THEN 
                            SET @items := (SELECT parents FROM en_folders_data WHERE items_id = _items_id);
                        WHEN "ru_folders_data" THEN 
                            SET @items := (SELECT parents FROM ru_folders_data WHERE items_id = _items_id);
                    END CASE;
		END IF;
		RETURN @items;
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
        DB::unprepared('DROP FUNCTION GetParentsOrChildren');
    }
}
