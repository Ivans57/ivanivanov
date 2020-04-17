<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureNestingLevelCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE NestingLevelCheck(IN _parent_id INT, IN previous_nesting_level INT, OUT next_nesting_level INT)
        BEGIN
            DECLARE next_parent_id INT;
            SET max_sp_recursion_depth = 8;
				
            IF (_parent_id IS NULL) 
		THEN SET next_nesting_level := previous_nesting_level;
            ELSE
		SET next_nesting_level := previous_nesting_level + 1;
		SET next_parent_id := (SELECT included_in_album_with_id FROM en_albums WHERE id = _parent_id);

		CALL NestingLevelCheck(next_parent_id, next_nesting_level, @nesting_level);
		SET next_nesting_level := @nesting_level;
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
        DB::unprepared('DROP PROCEDURE NestingLevelCheck');
    }
}
