<?php

namespace App;

//Depending on localization, some particular set of tables will be used,
//that's why need to extend Model adding language check function into constructor.
use App\BasicModel;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class Folder extends BasicModel{
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'folder_name', 'created_at', 'updated_at', 'is_visible', 'included_in_folder_with_id'];   
    
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
