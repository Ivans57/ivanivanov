<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCheckKeywordBeforeRuKeywordsDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `check_keyword_before_ru_keywords_delete` BEFORE DELETE ON `ru_keywords` FOR EACH ROW
            BEGIN

                DECLARE specialty CONDITION FOR SQLSTATE "45000";
                DECLARE _keyword_to_check nvarchar(55);

                SET _keyword_to_check = (SELECT keyword FROM ru_main_links WHERE keyword=OLD.keyword); 

                IF (_keyword_to_check = OLD.keyword) THEN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Can not delete the keyword, because it exists in main_links table";
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
        DB::unprepared('DROP TRIGGER `check_keyword_before_ru_keywords_delete`');
    }
}
