<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCheckKeywordBeforeRuAlbumsInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `check_keyword_before_ru_albums_insert` BEFORE INSERT ON `ru_albums` FOR EACH ROW
            BEGIN
                
                DECLARE specialty CONDITION FOR SQLSTATE "45000";

                #As the same Kyeword Check rules will be applied for Albums, Folders and Pictires,
                #the same procedure will be used for all three of them.
                CALL CheckFolderKeyword(NEW.keyword, @word_check_result);

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
        DB::unprepared('DROP TRIGGER `check_keyword_before_ru_albums_insert`');
    }
}
