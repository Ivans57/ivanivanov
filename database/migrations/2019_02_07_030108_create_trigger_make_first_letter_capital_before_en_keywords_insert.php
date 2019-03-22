<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerMakeFirstLetterCapitalBeforeEnKeywordsInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER make_first_letter_capital_before_en_keywords_insert BEFORE INSERT ON `en_keywords` FOR EACH ROW
            BEGIN
                SET NEW.keyword = CONCAT(UCASE(LEFT(NEW.keyword, 1)),
                SUBSTRING(NEW.keyword, 2));
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
        DB::unprepared('DROP TRIGGER `make_first_letter_capital_before_en_keywords_insert`');
    }
}
