<?php

namespace App\Http\Repositories;

use App\Article;

class AdminArticleRepository {
        
    //We need this to make a check for keyword uniqueness when adding a new
    //article keyword or editing existing.
    public function get_all_articles_keywords() {
        
        $all_articles_keywords = Article::all('keyword');       
        $articles_keywords_array = array();
        
        foreach ($all_articles_keywords as $articles_keyword) {
            array_push($articles_keywords_array, $articles_keyword->keyword);
        }    
        return $articles_keywords_array;   
    }
}