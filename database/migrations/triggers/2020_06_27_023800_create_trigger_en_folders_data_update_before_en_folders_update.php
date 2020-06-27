<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerEnFoldersDataUpdateBeforeEnFoldersUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `en_folders_data_update_before_en_folders_update` BEFORE UPDATE ON `en_folders` FOR EACH ROW
            BEGIN
		DECLARE _counter INT DEFAULT 0;
	
		IF ((NEW.included_in_folder_with_id <=> OLD.included_in_folder_with_id) = 0) THEN
		
                    CALL GetDataForAlbumsOrFolderDataUpdate(OLD.id, NEW.included_in_folder_with_id, "en_folders_data", @items_children, @items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev);
			
                    CALL UpdateNestingLevelInAlbumsOrFolderData(OLD.id, NEW.included_in_folder_with_id, @items_children, @items_children_with_current_item, "en_folders_data");
                    CALL UpdateParentsInAlbumsOrFolderData(OLD.id, NEW.included_in_folder_with_id, @items_children, @items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev, "en_folders_data");			
                    CALL UpdateChildrenInAlbumsOrFolderData(@items_children_with_current_item, @items_parents_to_remove, @new_parents_without_prev, "en_folders_data", "children");
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
        DB::unprepared('DROP TRIGGER `en_folders_data_update_before_en_folders_update`');
    }
}