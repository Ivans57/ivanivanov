<?php

//!!!We need this model for main link below the main title!!!

namespace App;

//Check in a future is it possible to do all the procedures below by middleware?
//use App\Http\Middleware;

use Illuminate\Database\Eloquent\Model;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class MainLink extends Model
{
    
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'link_name'];
        
        $this->check_lang();
    }   
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_main_links';
    
    } else {
        
        $this->table = 'ru_main_links';
        
    }
    }
    
}