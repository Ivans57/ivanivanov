<?php

//!!!We need this model for "Home" link!!!

/* Need to find file 
vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php
and add in __construct the line
$this->check_lang();*/

namespace App;

use Illuminate\Database\Eloquent\Model;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

//As we are using database and localization in our project 
//our models will be a bit compilated.

class HomeContent extends Model {
    
    //We need these variables to make necessary settings for our model.
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'content'];
  
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_home_content';
    
    } else {
        
        $this->table = 'ru_home_content';
        
    }
    }   
}
