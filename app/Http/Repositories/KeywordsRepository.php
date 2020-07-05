<?php

namespace App\Http\Repositories;

class KeywordsRepository {
     
    //We need this to make a check for keyword uniqueness.
    public function get_all_keywords() {
        
        $all_keywords = \App\Keyword::all('keyword');       
        $keywords_array = array();
        
        foreach ($all_keywords as $keyword) {
            array_push($keywords_array, $keyword->keyword);
        }    
        return $keywords_array;   
    }
}
