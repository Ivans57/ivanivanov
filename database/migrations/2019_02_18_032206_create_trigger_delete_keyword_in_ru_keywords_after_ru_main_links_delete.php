<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerDeleteKeywordInRuKeywordsAfterRuMainLinksDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `delete_keyword_in_ru_keywords_after_ru_main_links_delete` AFTER DELETE ON `ru_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE _keyword_to_delete nvarchar(55);

                SET _keyword_to_delete = (SELECT keyword FROM ru_keywords WHERE keyword=OLD.keyword);

                IF (_keyword_to_delete = OLD.keyword) THEN DELETE FROM ru_keywords WHERE keyword = OLD.keyword;
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
        DB::unprepared('DROP TRIGGER `delete_keyword_in_ru_keywords_after_ru_main_links_delete`');
    }
}
