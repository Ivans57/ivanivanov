<?php

//!!!We need this model for main link below the main title!!!

namespace App;

//Depending on localization, some particular set of tables will be used,
//that's why need to extend Model adding language check function into constructor.
use App\BasicModel;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class MainLink extends BasicModel {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword'];  
    
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    //This method is called from main Model which is inherited
    //Need to be carefull because main Model is ignored by Git.
    //Possibly this problem can be solved using interfaces as we can' t inherit several classes
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_main_links';
    
    } else {
        
        $this->table = 'ru_main_links';
        
    }
    }   
}