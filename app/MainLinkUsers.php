<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainLinkUsers extends Model
{
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['full_access_users', 'limited_access_users'];
    
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from BasicModel constructor.
    public function check_lang() {
        
        if (App::isLocale('en')) {
            
        $this->table = 'en_main_links_users'; 
        
        } else {
            
            $this->table = 'ru_main_links_users';
        }
    }
    
    public function user() {
        return $this->belongsTo('App\MainLink');
    }
}
