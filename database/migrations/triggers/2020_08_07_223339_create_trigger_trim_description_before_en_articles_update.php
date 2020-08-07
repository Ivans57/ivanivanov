<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerTrimDescriptionBeforeEnArticlesUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `trim_description_before_en_articles_update` BEFORE UPDATE ON `en_articles` FOR EACH ROW
            BEGIN
                IF ((NEW.article_description != OLD.article_description) OR (OLD.article_description IS NULL) AND
                    (NEW.article_description IS NOT NULL) AND (LENGTH(NEW.article_description) > 1000)) THEN
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
        DB::unprepared('DROP TRIGGER `trim_description_before_en_articles_update`');
    }
}
