<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerEnAlbumsDataUpdateBeforeEnAlbumsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `en_albums_data_update_before_en_albums_update` BEFORE UPDATE ON `en_albums` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		IF ((NEW.included_in_album_with_id <=> OLD.included_in_album_with_id) = 0) THEN
		
                    CALL GetDataForAlbumsOrFolderDataUpdate(OLD.id, NEW.included_in_album_with_id, "en_albums_data", @items_children, @items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev);
			
                    CALL UpdateNestingLevelInAlbumsOrFolderData(OLD.id, NEW.included_in_album_with_id, @items_children, @items_children_with_current_item, "en_albums_data");
                    CALL UpdateParentsInAlbumsOrFolderData(OLD.id, NEW.included_in_album_with_id, @items_children, @items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev, "en_albums_data");			
                    CALL UpdateChildrenInAlbumsOrFolderData(@items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev, "en_albums_data", "children");
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
        DB::unprepared('DROP TRIGGER `en_albums_data_update_before_en_albums_update`');
    }
}