<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Picture extends Model {
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'picture_caption'];
        
        $this->check_lang();
    }   
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_pictures';
    
    } else {
        
        $this->table = 'ru_pictures';
        
    }
    }
}
