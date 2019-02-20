<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteKeywordInEnKeywordsAfterEnMainLinksDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `delete_keyword_in_en_keywords_after_en_main_links_delete` AFTER DELETE ON `en_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE _keyword_to_delete nvarchar(55);

                SET _keyword_to_delete = (SELECT keyword FROM en_keywords WHERE keyword=OLD.keyword);

                IF (_keyword_to_delete = OLD.keyword) THEN DELETE FROM en_keywords WHERE keyword = OLD.keyword;
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
        DB::unprepared('DROP TRIGGER `delete_keyword_in_en_keywords_after_en_main_links_delete`');
    }
}
