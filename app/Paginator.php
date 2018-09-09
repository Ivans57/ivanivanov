<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;


class Paginator extends Model
{
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'text'];
        
        $this->check_lang();
    }   
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_paginator';
    
    } else {
        
        $this->table = 'ru_paginator';
        
    }
    }
}
