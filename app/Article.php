<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Article extends Model {
    public $timestamps;
    
    protected $table;
    
    protected $fillable;
    
    public function __construct() {
    
        $this->timestamps = false;
    
        $this->fillable = ['keyword', 'article_title','article_description', 'article_body', 'article_author', 'article_source'];
        
        $this->check_lang();
    }   
    
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_articles';
    
    } else {
        
        $this->table = 'ru_articles';
        
    }
    }
}
