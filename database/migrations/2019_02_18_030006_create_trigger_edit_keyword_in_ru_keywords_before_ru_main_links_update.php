<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerEditKeywordInRuKeywordsBeforeRuMainLinksUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `edit_keyword_in_ru_keywords_before_ru_main_links_update` BEFORE UPDATE ON `ru_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                DECLARE _keyword_to_check nvarchar(55);
                DECLARE _keyword_to_update nvarchar(55);

                SET _keyword_to_check = (SELECT keyword FROM ru_keywords WHERE keyword=NEW.keyword);
                SET _keyword_to_update = (SELECT keyword FROM ru_keywords WHERE keyword=OLD.keyword);

                IF (_keyword_to_check = NEW.keyword) THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "New keyword already exists in keywords table";
                ELSEIF (_keyword_to_update = OLD.keyword) THEN UPDATE ru_keywords SET keyword = NEW.keyword, text = NEW.link_name WHERE keyword = OLD.keyword;
                ELSE INSERT INTO ru_keywords (keyword, text) VALUES (NEW.keyword, NEW.link_name);
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
        DB::unprepared('DROP TRIGGER `edit_keyword_in_ru_keywords_before_ru_main_links_update`');
    }
}
