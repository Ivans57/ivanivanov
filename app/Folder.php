<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Folder extends Model
{
    
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'folder_name'];
        
        $this->check_lang();
    }   
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_folders';
    
    } else {
        
        $this->table = 'ru_folders';
        
    }
    }
    
}
