<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCheckKeywordBeforeRuMainLinksInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `check_keyword_before_ru_main_links_insert` BEFORE INSERT ON `ru_main_links` FOR EACH ROW
            BEGIN
                
                DECLARE specialty CONDITION FOR SQLSTATE "45000";

                CALL CheckKeyword(NEW.keyword, @word_check_result);

                IF (!@word_check_result) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "New keyword contains restricted characters";
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
        DB::unprepared('DROP TRIGGER `check_keyword_before_ru_main_links_insert`');
    }
}
