<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerCheckKeywordBeforeEnFoldersInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `check_keyword_before_en_folders_insert` BEFORE INSERT ON `en_folders` FOR EACH ROW
            BEGIN
                CALL CheckKeyword(NEW.keyword, @word_check_result);

                IF (!@word_check_result) THEN
                    SIGNAL SQLSTATE "45000";
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
        DB::unprepared('DROP TRIGGER `check_keyword_before_en_folders_insert`');
    }
}