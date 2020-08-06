<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerTrimDescriptionBeforeRuArticlesInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `trim_description_before_ru_articles_insert` BEFORE INSERT ON `ru_articles` FOR EACH ROW
            BEGIN
                IF ((NEW.article_description IS NOT NULL) AND (LENGTH(NEW.article_description) > 1000)) THEN
                    SET NEW.article_description = SUBSTRING(NEW.article_description, 1, 1000);
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
        DB::unprepared('DROP TRIGGER `trim_description_before_ru_articles_insert`');
    }
}
