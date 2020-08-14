<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//Depending on localization, some particular set of tables will be used,
//that's why need to extend Model adding language check function into constructor.

class BasicModel extends Model
{
    public function __construct(){
        $this->check_lang();
    }
}
