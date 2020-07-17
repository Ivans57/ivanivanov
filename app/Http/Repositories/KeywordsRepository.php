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
