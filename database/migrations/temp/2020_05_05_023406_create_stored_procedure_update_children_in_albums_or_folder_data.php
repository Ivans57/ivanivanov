<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureUpdateChildrenInAlbumsOrFolderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE UpdateChildrenInAlbumsOrFolderData(IN _children JSON, IN _old_parent_ids JSON, IN _new_parent_ids JSON, IN _table_name VARCHAR(45), IN _field_name VARCHAR(45))
            BEGIN		
		IF (_old_parent_ids IS NOT NULL) THEN		
                    CALL RemoveOldChildren(_children, _old_parent_ids, _table_name, _field_name);			
		END IF;
				
		IF (_new_parent_ids IS NOT NULL) THEN												
                    CALL InsertNewChildren(_children, _new_parent_ids, _table_name, _field_name);
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
        DB::unprepared('DROP PROCEDURE UpdateChildrenInAlbumsOrFolderData');
    }
}
