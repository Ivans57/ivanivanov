<?php

namespace App;

//Depending on localization, some particular set of tables will be used,
//that's why need to extend Model adding language check function into constructor.
use App\BasicModel;

//We need this for localization. 
//Particulary here we need to check what language do we have, so we can choose 
//a proper table. Look at App::isLocale('en')
use App;

class Article extends BasicModel {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['keyword', 'article_title','article_description', 'article_body', 'article_author', 'article_source', 
                            'folder_id', 'created_at', 'updated_at', 'is_visible'];
    
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
