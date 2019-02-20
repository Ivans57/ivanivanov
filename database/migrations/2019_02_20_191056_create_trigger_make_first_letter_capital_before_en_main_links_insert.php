<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerMakeFirstLetterCapitalBeforeEnMainLinksInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `make_first_letter_capital_before_en_main_links_insert` BEFORE INSERT ON `en_main_links` FOR EACH ROW
            BEGIN

                SET NEW.keyword = CONCAT(UCASE(LEFT(NEW.keyword, 1)),
                SUBSTRING(NEW.keyword, 2));

                SET NEW.link_name = CONCAT(UCASE(LEFT(NEW.link_name, 1)),
                SUBSTRING(NEW.link_name, 2));

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
        DB::unprepared('DROP TRIGGER `make_first_letter_capital_before_en_main_links_insert`');
    }
}
