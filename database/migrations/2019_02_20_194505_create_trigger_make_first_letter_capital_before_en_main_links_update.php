<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerMakeFirstLetterCapitalBeforeEnMainLinksUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `make_first_letter_capital_before_en_main_links_update` BEFORE UPDATE ON `en_main_links` FOR EACH ROW
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
        DB::unprepared('DROP TRIGGER `make_first_letter_capital_before_en_main_links_update`');
    }
}
