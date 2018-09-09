<?php

//!!!We need this model for the main title!!!

namespace App;

use Illuminate\Database\Eloquent\Model;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class MainFooter extends Model
{
    
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'main_footer_title'];
        
        $this->check_lang();
    }
           
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_main_footer';
    
    } else {
        
        $this->table = 'ru_main_footer';
        
    }
    }
    
}
