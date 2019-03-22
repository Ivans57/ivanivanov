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
                
                SET NEW.link_name = CONCAT(UCASE(LEFT(NEW.link_name, 1)),
                SUBSTRING(NEW.link_name, 2));

                #We need to perform another field check as if we don not do this,
                #we will be unable to update record, because system will try
                #to assign to an old keyword field value the same new value
                #which will cause an error.
                IF (_keyword_to_check = NEW.keyword AND OLD.link_name = 
                NEW.link_name AND OLD.web_link_name = NEW.web_link_name AND OLD.admin_web_link_name = 
                NEW.admin_web_link_name) THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "New keyword already exists in keywords table";
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
