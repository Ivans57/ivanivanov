<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class Article extends Model {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'article_title','article_description', 'article_body', 'article_author', 'article_source', 'is_visible'];
    
    //Here we are making a method which according to a current language 
    //will select a proper table.
    //This method is called from main parent model's constructor.
    public function check_lang(){
        
        if (App::isLocale('en')) {
        
        $this->table = 'en_articles';
    
    } else {
        
        $this->table = 'ru_articles';
        
    }
    }
}
