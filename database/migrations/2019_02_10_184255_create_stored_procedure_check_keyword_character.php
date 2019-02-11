<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoredProcedureCheckKeywordCharacter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //As there are some issues with passing quoted text direcltly to DB::unprepared statement,
        //first we need to make MySql query with * instead of ' and then we nedd to
        //replace * with '.
        $CheckKeywordCharacter = '
        CREATE PROCEDURE CheckKeywordCharacter(IN _character nvarchar(1), OUT _character_check_result bool)
            BEGIN
                DECLARE _counter INT DEFAULT 0;

                SET _character_check_result = false;

                SET @valid_values = *["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"]*;

                SET @character_value = *[]*;

                SET @character_value = JSON_ARRAY_APPEND(@character_value, *$*, _character);

                characterloop: WHILE (_counter < JSON_LENGTH(@valid_values)) DO
    
                IF (STRCMP(JSON_EXTRACT(@character_value, *$[0]*), JSON_EXTRACT(@valid_values, CONCAT(*$[*,_counter,*]*))) = 0) THEN SET _character_check_result = true;
                LEAVE characterloop;
                END IF;

                SET _counter = _counter + 1;
    
            END WHILE characterloop;
            END
        ';
        $CheckKeywordCharacter = str_replace("*","'", $CheckKeywordCharacter);
        DB::unprepared($CheckKeywordCharacter);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE CheckKeywordCharacter');
    }
}
