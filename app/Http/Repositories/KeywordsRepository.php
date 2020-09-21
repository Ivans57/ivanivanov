<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Keyword;

class KeywordsRepository {
    
    public function store($request) {
        $input = $request->all();
               
        $input['created_at'] = Carbon::now();        
        $input['updated_at'] = Carbon::now();
        
        Keyword::create($input);
    }
    
    public function update($keyword, $request) {
        $edited_keyword = Keyword::where('keyword', '=', $keyword)->firstOrFail();
        
        $input = $request->all();      
        $input['updated_at'] = Carbon::now();
        
        $edited_keyword->update($input);
    }
    
    public function destroy($keywords) {
        $keywords_array = $this->get_keywords_from_string($keywords);
        foreach ($keywords_array as $keyword) {
            Keyword::where('keyword', '=', $keyword)->delete();
        }
    }
    
    //As it is not possible to send an array in get request, all keywords are sent in one string, 
    //after this string comes to controller it needs to be split to get necessary data.
    public function get_keywords_from_string($keywords) {
        //All keywords are coming as one string. They are separated by ";"
        $keywords_array = explode(";", $keywords);
        //The function below removes the last (empty) element of the array.
        array_pop($keywords_array);
        
        return $keywords_array;
    }
    
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
