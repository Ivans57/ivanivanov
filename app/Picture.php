<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Picture extends Model {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'picture_caption', 'included_in_album_with_id', 'created_at', 'updated_at', 'file_name', 'is_visible'];
    
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_pictures';
    
    } else {
        
        $this->table = 'ru_pictures';
        
    }
    }
}
