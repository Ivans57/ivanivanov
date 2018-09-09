<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

//As we are using database and localization in our project 
//our models will be a bit compilated.

class Keyword extends Model
{
    //We need these variables to make necessary settings for our model.
    
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    //We need this constructor to initialize our variables, so our settings 
    //can take an effect.
    //Our constructor is underlined by yellow line! 
    //Find out in a future what is a problem?
    
    public function __construct() {
    
        //As we dont' need any timestamps in this model 
        //we set timestamps to false.
        $this->timestamps = false;
    
        //In this line we are lisitng fields from our table 
        //which are allowed for a mass assignment.
        $this->fillable = ['keyword', 'text'];
        
        //Here we are calling a method which according to a current language 
        //will select a proper table.
        $this->check_lang();
    }
     
    //Here we are making a method which according to a current language 
    //will select a proper table.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_keywords';
    
    } else {
        
        $this->table = 'ru_keywords';
        
    }
    }
}
