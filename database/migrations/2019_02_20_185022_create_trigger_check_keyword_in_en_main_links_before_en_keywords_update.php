<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCheckKeywordInEnMainLinksBeforeEnKeywordsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `check_keyword_in_en_main_links_before_en_keywords_update` BEFORE UPDATE ON `en_keywords` FOR EACH ROW
            BEGIN

                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                DECLARE _keyword_to_check nvarchar(55);
                
                SET _keyword_to_check = (SELECT keyword FROM en_main_links WHERE keyword=OLD.keyword); 

                IF (_keyword_to_check = OLD.keyword AND OLD.keyword != NEW.keyword) 
                THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Can not update the keyword, because it exists in en_main_links table";
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
        DB::unprepared('DROP TRIGGER `check_keyword_in_en_main_links_before_en_keywords_update`');
    }
}
