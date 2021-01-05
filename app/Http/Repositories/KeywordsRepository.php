<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Keyword;

class KeywordsWithPaginationInfo {
    public $all_keywords_count;
    public $keywords_on_page;
    public $sorting_asc_or_desc;
    //It is better to keep this property here,
    //so in case of empty items array we don't need
    //to make an object.
    //public $total_number_of_items;
    public $paginator_info;   
}

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
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('keyword', 'desc')->get());
                $sorting_asc_or_desc["Keyword"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_keyword_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('keyword', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('keyword', 'asc')->get());
                $sorting_asc_or_desc["Keyword"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_text_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('text', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('text', 'desc')->get());
                $sorting_asc_or_desc["Text"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_text_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('text', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('text', 'asc')->get());
                $sorting_asc_or_desc["Text"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_section_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('section', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('section', 'desc')->get());
                $sorting_asc_or_desc["Section"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_section_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('section', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('section', 'asc')->get());
                $sorting_asc_or_desc["Section"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_creation_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::latest()->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->latest()->get());
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_creation_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('created_at', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('created_at', 'asc')->get());
                $sorting_asc_or_desc["Creation"] = ["desc" , 1];
                break;
            case ('keywords_sort_by_update_desc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('updated_at', 'desc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('updated_at', 'desc')->get());
                $sorting_asc_or_desc["Update"] = ["asc" , 1];
                break;
            case ('keywords_sort_by_update_asc'):
                $keywords = ($search_mode_is_on === 0) ? (Keyword::orderBy('updated_at', 'asc')->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->orderBy('updated_at', 'asc')->get());
                $sorting_asc_or_desc["Update"] = ["desc" , 1];
                break;
            default:
                $keywords = ($search_mode_is_on === 0) ? (Keyword::latest()->paginate($items_amount_per_page)) : 
                            (Keyword::where('text', 'LIKE', '%'.$keywords_text.'%')->latest()->get());
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
    
    //This function is used for search.
    public function getKeywordsFromSearch($keywords_text, $page, $items_amount_per_page, $sorting_mode = null) {        
        $keywords_with_pagination = new KeywordsWithPaginationInfo();
        
        //In the next line the data are getting extracted from the database and sorted.        
        $all_keywords = $this->sort(1, $items_amount_per_page, $sorting_mode, $keywords_text);
        
        $keywords_with_pagination->all_keywords_count = sizeof($all_keywords["keywords"]);
        
        $keywords_with_pagination->sorting_asc_or_desc = $all_keywords["sorting_asc_or_desc"];
        
        //Later keywords array has to be chunked to separate pieces for pagination.
        //When getting data from the database it is not a pure array, it is an object and it cannot be chunked.
        //For that reason these data need to be converted to an array.
        $all_keywords_array = [];
        
        foreach ($all_keywords["keywords"] as $one_keyword){
           array_push($all_keywords_array, $one_keyword); 
        }
        
        //The following information we can have only if we have at least one item.
        if($keywords_with_pagination->all_keywords_count > 0) {
            //The line below cuts all data into pages.
            //We can do it only if we have at least one item in the array of the full data.
            $keywords_cut_into_pages = array_chunk($all_keywords_array, $items_amount_per_page, false);
            $keywords_with_pagination->paginator_info = (new CommonRepository())->get_paginator_info($page, $keywords_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            $keywords_with_pagination->keywords_on_page = $keywords_with_pagination->paginator_info->number_of_pages >= $page ? 
                                                                $keywords_cut_into_pages[$page-1] : null;
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showAlbumView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $keywords_with_pagination->paginator_info = new Paginator();
            $keywords_with_pagination->paginator_info->number_of_pages = 1;
        }
        
        return $keywords_with_pagination;
    }
}
