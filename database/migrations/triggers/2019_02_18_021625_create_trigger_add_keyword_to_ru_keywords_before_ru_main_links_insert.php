<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAddKeywordToRuKeywordsBeforeRuMainLinksInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `add_keyword_to_ru_keywords_before_ru_main_links_insert` BEFORE INSERT ON `ru_main_links` FOR EACH ROW
            BEGIN
            
                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                DECLARE _keyword_to_check nvarchar(55);
                
                SET _keyword_to_check = (SELECT keyword FROM ru_keywords WHERE keyword=NEW.keyword); 

                IF (_keyword_to_check = NEW.keyword) THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "New keyword already exists in keywords table";
                ELSE 

                INSERT INTO ru_keywords (keyword, text, created_at, updated_at) VALUES (NEW.keyword, NEW.keyword, now(), now());
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
        DB::unprepared('DROP TRIGGER `add_keyword_to_ru_keywords_before_ru_main_links_insert`');
    }
}
