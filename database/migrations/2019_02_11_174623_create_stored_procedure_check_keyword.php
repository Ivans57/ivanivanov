<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureCheckKeyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE CheckKeyword(IN _word nvarchar(55), OUT _word_check_result bool)
            BEGIN
                DECLARE _word_length int DEFAULT LENGTH(_word);

                DECLARE _character nvarchar(1);

                DECLARE _counter INT DEFAULT 0;


                SET _word_check_result = true;


                wordloop: WHILE (_counter < _word_length) DO
    
                SET _character = SUBSTRING(_word FROM (_counter-_word_length) FOR 1);

                CALL CheckKeywordCharacter(_character, @character_check_result);

                IF (!@character_check_result) THEN SET _word_check_result = false;
                LEAVE wordloop;
                END IF;

                SET _counter = _counter + 1;
    
                END WHILE wordloop;
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
        DB::unprepared('DROP PROCEDURE CheckKeyword');
    }
}
