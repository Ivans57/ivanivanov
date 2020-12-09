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
    
    //The method below is to sort keywords in different sorting modes.
    //This function is operating in two modes: normal and serach.
    //Normal mode is just to show the contents of the section,
    //Search mode is to display keywords with particular text.
    public function sort($search_mode_is_on, $items_amount_per_page, $sorting_mode, $keywords_text = null) {
        //This array is required to show sorting arrows properly.
        $sorting_asc_or_desc = ["Keyword" => ["desc" , 0], "Text" => ["desc" , 0], "Section" => ["desc" , 0], 
                                "Creation" => ["desc" , 0], "Update" => ["desc" , 0],];             
        $keywords = null;
        
        switch ($sorting_mode) {
            case ('keywords_sort_by_keyword_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('keyword', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('keyword', 'desc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Keyword"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_keyword_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('keyword', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('keyword', 'asc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Keyword"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_text_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('text', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('text', 'desc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Text"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_text_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('text', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('text', 'asc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Text"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_section_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('section', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', $keywords_text)->orderBy('section', 'desc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Section"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_section_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('section', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('section', 'asc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Section"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_creation_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::latest()->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->latest()->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_creation_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('created_at', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('created_at', 'asc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Creation"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_update_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('updated_at', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('updated_at', 'desc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Update"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_update_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('updated_at', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', '=', '%'.$keywords_text.'%')->orderBy('updated_at', 'asc')->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Update"] = ["desc" , 1];
                break;
            default:
                $keywords = ($search_mode_is_on === 0) ? (Keyword::latest()->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->latest()->paginate($items_amount_per_page));
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
        }     
        return ["keywords" => $keywords, "sorting_asc_or_desc" => $sorting_asc_or_desc];
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
