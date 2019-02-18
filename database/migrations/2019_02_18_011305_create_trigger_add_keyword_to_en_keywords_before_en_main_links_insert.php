<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAddKeywordToEnKeywordsBeforeEnMainLinksInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `add_keyword_to_en_keywords_before_en_main_links_insert` BEFORE INSERT ON `en_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                DECLARE _keyword_to_check nvarchar(55);
                
                SET _keyword_to_check = (SELECT keyword FROM en_keywords WHERE keyword=NEW.keyword); 

                IF (_keyword_to_check = NEW.keyword) THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "New keyword already exists in keywords table";
                ELSE INSERT INTO en_keywords (keyword, text) VALUES (NEW.keyword, NEW.link_name);
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
        DB::unprepared('DROP TRIGGER `add_keyword_to_en_keywords_before_en_main_links_insert`');
    }
}
