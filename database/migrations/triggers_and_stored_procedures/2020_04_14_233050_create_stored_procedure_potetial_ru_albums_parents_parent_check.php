<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedurePotetialRuAlbumsParentsParentCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE PotetialRuAlbumsParentsParentCheck(IN _being_changed_album_id INT, IN _potential_parent_album_id INT)
            BEGIN
                DECLARE potential_parent_album_parent_id INT;
                
                SET max_sp_recursion_depth=10;

                SET potential_parent_album_parent_id := (SELECT included_in_album_with_id FROM ru_albums WHERE id = _potential_parent_album_id);
				
		IF (potential_parent_album_parent_id = _being_changed_album_id) 
                    THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "The destination album is a child album of the source album.";
		ELSEIF (potential_parent_album_parent_id) AND (potential_parent_album_parent_id != _being_changed_album_id) 
                    THEN CALL PotetialRuAlbumsParentsParentCheck(_being_changed_album_id, potential_parent_album_parent_id);
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
        DB::unprepared('DROP PROCEDURE PotetialRuAlbumsParentsParentCheck');
    }
}
