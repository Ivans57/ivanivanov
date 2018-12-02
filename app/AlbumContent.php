<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class AlbumContent extends Model {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'text'];   
               
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_album_content';

    } else {
        
        $this->table = 'ru_album_content';
        
    }
    }
}
