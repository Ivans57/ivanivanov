<?php

namespace App\Http\Repositories;

//We need the line below to use localization 
use App;

//We need the class below to make an array of this class objects which will 
//contain only database data we need for the relevant view.
//Need to check if I met all rules about writing the class below and its properties.
//For example of all small and capital letters
class MainLinkForView {
    public $keyWord;
    public $linkName;
    public $webLinkName;
    public $adminWebLinkName;
    public $isActive = false;
}

//We need the class below to make an object which will contain an array of all main links
//for navigation menu and also will show the status of additional keywords link.
//We use this only for Administration Panel.     
class MainLinksAndKeywordLinkCheck {
    public $mainLinks;
    public $keywordsLinkIsActive;
}

class Paginator {
    public $number_of_pages;
    public $current_page;
    public $previous_page;
    public $next_page;
}

class CommonRepository {
    
    //This method we need to use only when we are working with website, as we 
    //don't show there keywords link
    public function get_main_links ($current_page) {
        
        //On the line below we can see an old example of using my localization.
        //We don't need it. I left it for information purposes.        
        //Lang::setLocale('en');
        
        //Here we are fetching from the database full information about our main links      
        $main_links_full = \App\MainLink::all();
        
        //Here we are making an empty dynamic array
        $main_links_info = array();
        
        //Here we are filling our dynamic array with MainLinkForView class elements
        //and saving in there the data which we will need to use in our view
        for($i = 0; $i < count($main_links_full); $i++) {
            //$main_links_info[$i]->keyWord = $main_links_full[$i]->keyword;
            $main_links_info[$i] = new MainLinkForView();
            $main_links_info[$i]->keyWord = $main_links_full[$i]->keyword;
            $main_links_info[$i]->linkName = $this->get_link_name($main_links_full[$i]->keyword);
            $main_links_info[$i]->webLinkName = $main_links_full[$i]->web_link_name;
            $main_links_info[$i]->adminWebLinkName = $main_links_full[$i]->admin_web_link_name;
        }
        
        //Two lines before I left for example how to find an index of array element
        //containing some data we are looking for
        //array_search("b",$cArray);
        //$check_index = array_search("Home",$main_links_info);
                
        //In the loop below we are checking every single element of main_links_info
        //array for containing in its keyWord property a certain keyword we are passing.
        //If it does we are setting its isActive property to true.
        foreach($main_links_info as $main_link_info) {
            //$check = $main_link_info;
            if ($main_link_info->keyWord == $current_page){
                $main_link_info->isActive = true;
            }
        }
      
        return $main_links_info;
    }
    
    //This method allows to get an actual text for the link from keywords table
    //knowing its keyword
    public function get_link_name ($link_keyword) {
        $link_name = \App\Keyword::where('keyword', $link_keyword)->first();
        return $link_name->text;
    }
      
    //This method we need to use only when we are working with admin panel, as we 
    //need to show there keywords link. This method is getting all main links for navigation menu
    //and also checking whether keywords link is active 
    public function get_main_links_and_keywords_link_status ($current_page) {
        
        $main_links_and_keywords_link_status = new MainLinksAndKeywordLinkCheck();        
        $main_links_and_keywords_link_status->mainLinks = $this->get_main_links($current_page);        
        $main_links_and_keywords_link_status->keywordsLinkIsActive = $this->active_link_search($main_links_and_keywords_link_status->mainLinks);
        
        return $main_links_and_keywords_link_status;
        
    }
    
    //This method gets all necessary information for paginator
    public function get_paginator_info($page, $all_items_collection_cut_into_pages) {
        
        $paginator_info = new Paginator();
        
        $paginator_info->number_of_pages = count($all_items_collection_cut_into_pages);    
        $paginator_info->current_page = $page;       
        $paginator_info->previous_page = $paginator_info->current_page - 1;
        $paginator_info->next_page = $paginator_info->current_page + 1;
        
        return $paginator_info;
    }

    //This function checks the localization and redirects to the last page in
    //case user enters a page number more than actucal numebr of pages.
    public function redirect_to_last_page_one_entity($section, $last_page, $is_admin_panel){              
        if ($is_admin_panel) {
            if (App::isLocale('en')) {
                return redirect('admin/'.$section.'?page='.$last_page);
            } else {
                return redirect('ru/'.'admin/'.$section.'?page='.$last_page);
            }
        } else {
            if (App::isLocale('en')) {
                return redirect($section.'?page='.$last_page);
            } else {
                return redirect('ru/'.$section.'?page='.$last_page);
            }
        }        
    }
    
    //This function checks the localization and redirects to the first page in
    //case user enters a page number less then 1.
    public function redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel){       
        if ($is_admin_panel) {
            if (App::isLocale('en')) {
                return redirect('admin/'.$section.'/'.$keyword.'/page/1');
            } else {
                return redirect('ru/'.'admin/'.$section.'/'.$keyword.'/page/1');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect($section.'/'.$keyword.'/page/1');
            } else {
                return redirect('ru/'.$section.'/'.$keyword.'/page/1');
            }
        }
    }
    
    //This function checks the localization and redirects to the last page in
    //case user enters a page number more than actucal numebr of pages.
    public function redirect_to_last_page_multi_entity($section, $keyword, $last_page, $is_admin_panel){       
        if ($is_admin_panel) {
            if (App::isLocale('en')) {
                return redirect('admin/'.$section.'/'.$keyword.'/page/'.$last_page);
            } else {
                return redirect('ru/'.'admin/'.$section.'/'.$keyword.'/page/'.$last_page);
            }
        } else {
            if (App::isLocale('en')) {
                return redirect($section.'/'.$keyword.'/page/'.$last_page);
            } else {
                return redirect('ru/'.$section.'/'.$keyword.'/page/'.$last_page);
            }
        }
    }
    
    private function active_link_search($all_main_links) {
        $array_does_not_have_active_links = true;
        foreach($all_main_links as $main_link) {
            if ($main_link->isActive == true){
                $array_does_not_have_active_links = false;
                return $array_does_not_have_active_links;                
            }            
            }
        return $array_does_not_have_active_links;            
            }
    }
