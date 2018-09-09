<?php

namespace App\Http\Repositories;

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

class CommonRepository {
    
    public function get_main_links($rurrent_page) {
        
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
            $main_links_info[$i]->linkName = $main_links_full[$i]->link_name;
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
            if ($main_link_info->keyWord == $rurrent_page){
                $main_link_info->isActive = true;
            }
        }
        
        return $main_links_info;
    }
    
}
