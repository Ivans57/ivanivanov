<?php

/* Need to find file 
vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php
and add in __construct the line
$this->check_lang();*/

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Folder extends Model{
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'folder_name'];   
    
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_folders';
    
    } else {
        
        $this->table = 'ru_folders';
        
    }
    }  
}
