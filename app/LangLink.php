<?php

//!!!We need this model for language links!!!

namespace App;

use Illuminate\Database\Eloquent\Model;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class LangLink extends Model
{
    
    //If we did not have a localization it would be enough
    //just to assign necessary variables as we can see in 
    //next three lines below.
    /*public $timestamps = false;
    
    protected $table = 'language_links';
    
    protected $fillable = ['language'];*/
    
    
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'language'];
        
        $this->check_lang();
    }      
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_language_links';
    
    } else {
        
        $this->table = 'ru_language_links';
        
    }
    }
    
}
