<?php

namespace App\Http\Repositories;

//We need the line below to use localization.
use App;
use \App\MainLink;
use \App\AdminLink;
use \App\Keyword;
use Illuminate\Support\Facades\Auth;
use App\Http\Repositories\AlbumsRepository;
use App\Http\Repositories\ArticlesRepository;


//Below is a class for main links of the website and admin panel.
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
class AllMainLinks {
    public $mainWSLinks;
    public $mainAPLinks;
}

class Paginator {
    public $number_of_pages;
    public $current_page;
    public $previous_page;
    public $next_page;
}

class CommonRepository {
    
    //This method we need to use only when we are working with website.
    public function get_main_website_links ($current_page, $is_admin_panel) {
        
        //On the line below we can see an old example of using my localization.
        //We don't need it. I left it for information purposes.        
        //Lang::setLocale('en');
        
        //Here we are fetching from the database full information about our main links      
        $main_links_full = MainLink::all();
        
        //The code in the if condition is required to make the website work even the database is empty
        if (is_null($main_links_full)){
            return null;
        }       
        //Here we are making an empty dynamic array.
        $main_links_info = array();
        
        if ($is_admin_panel === 0) {
            $main_links_info = $this->get_main_website_links_for_website($main_links_full);
        } else {
            $main_links_info = $this->get_main_website_links_for_adminpanel($main_links_full);
        }
                
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
    
    //As for website and admin panel are different conditions of showing main links, I made two separate methods for them.
    private function get_main_website_links_for_website($main_links_from_database) {
        //Here we are making an empty dynamic array.
        $main_links_info = array();
        for ($i = 0; $i < count($main_links_from_database); $i++) {
            //Here we are filling our dynamic array with MainLinkForView class elements
            //and saving in there the data which we will need to use in our view.
            $main_links_info[$i] = new MainLinkForView();
            $main_links_info[$i]->keyWord = $main_links_from_database[$i]->keyword;
            $main_links_info[$i]->linkName = $this->get_link_name($main_links_from_database[$i]->keyword);
            $main_links_info[$i]->webLinkName = $main_links_from_database[$i]->web_link_name;
            $main_links_info[$i]->adminWebLinkName = $main_links_from_database[$i]->admin_web_link_name;
        }
        return $main_links_info;
    }
    
    //As for website and admin panel are different conditions of showing main links, I made two separate methods for them.
    private function get_main_website_links_for_adminpanel($main_links_from_database) {
        //Here we are making an empty dynamic array.
        $main_links_info = array();
        foreach($main_links_from_database as $main_link_from_database) {
            if (Auth::user()->role_and_status->role === 'admin') {
                //Here we are filling our dynamic array with MainLinkForView class elements
                //and saving in there the data which we will need to use in our view.
                $main_link_info = new MainLinkForView();
                $main_link_info->keyWord = $main_link_from_database->keyword;
                $main_link_info->linkName = $this->get_link_name($main_link_from_database->keyword);
                $main_link_info->adminWebLinkName = $main_link_from_database->admin_web_link_name;
                array_push($main_links_info, $main_link_info);
            }
        }
        return $main_links_info;
    }
    
    //This function we need to use only when we are working with admin panel.
    private function get_main_admin_panel_links ($current_page) {
             
        //Here we are fetching from the database full information about admin panel main links.      
        $main_links_full = AdminLink::all();
        
        //The code in the if condition is required to make the admin panel work even the database is empty.
        if (is_null($main_links_full)){
            return null;
        }       
        //Here we are making an empty dynamic array
        $main_links_info = array();      
        //Here we are filling our dynamic array with MainLinkForView class elements
        //and saving in there the data which we will need to use in our view
        foreach($main_links_full as $main_link_from_full) {
            if ($main_link_from_full->keyword === 'Start' || Auth::user()->role_and_status->role === 'admin') {
                $main_link_info = new MainLinkForView();
                $main_link_info->keyWord = $main_link_from_full->keyword;
                $main_link_info->linkName = $this->get_link_name($main_link_from_full->keyword);
                $main_link_info->adminWebLinkName = $main_link_from_full->admin_web_link_name;
                array_push($main_links_info, $main_link_info);
            }
        }
              
        //In the loop below we are checking every single element of main_links_info
        //array for containing in its keyWord property a certain keyword we are passing.
        //If it does we are setting its isActive property to true.
        foreach($main_links_info as $main_link_info) {
            if ($main_link_info->keyWord == $current_page){
                $main_link_info->isActive = true;
            }
        }
      
        return $main_links_info;
    }
    
    //This method allows to get an actual text for the link from keywords table
    //knowing its keyword.
    public function get_link_name ($link_keyword) {
        $link_name = Keyword::where('keyword', $link_keyword)->first();
        //The if condition below is preventing an error if there is no keyword
        //added for a main link.
        if (is_null($link_name)){
            return $link_keyword;
        }
        return $link_name->text;
    }
      
    //The function below is required to get all necessary main links for admin panel.
    //Including pure admin panel main links and pure website main links.
    public function get_main_links_for_admin_panel_and_website ($current_page) {
        
        $all_main_links = new AllMainLinks();        
        $all_main_links->mainWSLinks = $this->get_main_website_links($current_page, 1);
        $all_main_links->mainAPLinks = $this->get_main_admin_panel_links($current_page);
        //$main_links_and_keywords_link_status->keywordsLinkIsActive = $this->active_link_search($main_links_and_keywords_link_status->mainLinks);
        
        return $all_main_links;
        
    }
    
    //For Keywords and Users (the method below).
    //As it is not possible to send an array in get request, all keywords (usernames) are sent in one string, 
    //after this string comes to controller it needs to be split to get necessary data.
    public function get_values_from_string($values) {
        //All keywords (usernames) are coming as one string. They are separated by ";"
        $values_array = explode(";", $values);
        //The function below removes the last (empty) element of the array.
        array_pop($values_array);
        
        return $values_array;
    }
    
    //This method gets all necessary information for paginator.
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
    public function redirect_to_last_page_multi_entity($section, $keyword, $last_page, $is_admin_panel) {       
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
    
    //As it is not possible to send an array in get request, all keywords and types of entities
    //are sent in one string, after this string comes to controller it needs to be split to get necessary data.
    public function get_directories_and_files_from_string($entity_types_and_keywords) {
        //All keywords are coming as one string. They are separated by ";"
        $directories_and_files = explode(";", $entity_types_and_keywords);
        //The function below removes the last (empty) element of the array.
        array_pop($directories_and_files);
        
        $directories = array();
        $files = array();
        
        foreach ($directories_and_files as $directory_or_file) {
            //Apart of keywords string in incoming parameter has indicators,
            //their purpose is to identify does this keyword belongs to foldre or article.
            //Keyword is separated from its indicator by "+".
            $directory_or_file_array = explode("+", $directory_or_file);
            //Depends what is in array, it needs to go to its own array.
            //Folders and Articles should be separate.
            if ($directory_or_file_array[0] == "directory") {
                array_push($directories ,$directory_or_file_array[1]);
            } else {
                array_push($files ,$directory_or_file_array[1]);
            }
        }       
        return $directories_and_files_array = [$directories, $files];
    }
    
    //The method below is to sort albums/folders(directories) and pictures/articles(files) in different modes.
    public function sort_for_albums_or_articles($search_mode_is_on, $items_amount_per_page, $sorting_mode, $including_invisible, 
                                                $what_to_sort, $parent_directory = null, $search_text = null, $is_admin_panel = null) {
        //This array is required to show sorting arrows properly.
        $sorting_asc_or_desc = ["Name" => ["desc" , 0], "Creation" => ["desc" , 0], "Update" => ["desc" , 0],];
        
        $directories_or_files = null;
        
        switch ($sorting_mode) {
            case ('sort_by_name_desc'):                
                if ($what_to_sort == 'albums' || $what_to_sort == 'included_albums' || $what_to_sort == 'included_pictures' || $what_to_sort == 'pictures'){
                    $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                    (($what_to_sort === 'included_pictures' || $what_to_sort === 'pictures') ? 'picture_caption' : 'album_name'), 'desc', 
                    $parent_directory, $search_text, $is_admin_panel);
                } else if ($what_to_sort == 'folders' || $what_to_sort == 'included_folders' || $what_to_sort == 'included_articles' || $what_to_sort == 'articles') {
                    $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                    (($what_to_sort === 'included_articles' || $what_to_sort === 'articles') ? 'article_title' : 'folder_name'), 'desc', 
                    $parent_directory, $search_text, $is_admin_panel);
                }
                $sorting_asc_or_desc["Name"] = ["asc" , 1];
                break;
            case ('sort_by_name_asc'):                
                if ($what_to_sort == 'albums' || $what_to_sort == 'included_albums' || $what_to_sort == 'included_pictures' || $what_to_sort == 'pictures'){
                    $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                    (($what_to_sort === 'included_pictures' || $what_to_sort === 'pictures') ? 'picture_caption' : 'album_name'), 'asc', 
                    $parent_directory, $search_text, $is_admin_panel);
                } else if ($what_to_sort == 'folders' || $what_to_sort == 'included_folders' || $what_to_sort == 'included_articles' || $what_to_sort == 'articles') {
                    $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                    (($what_to_sort === 'included_articles' || $what_to_sort === 'articles') ? 'article_title' : 'folder_name'), 'asc', 
                    $parent_directory, $search_text, $is_admin_panel);
                }
                $sorting_asc_or_desc["Name"] = ["desc" , 1];
                break;
            case ('sort_by_creation_desc'):
                $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'desc', $parent_directory, $search_text, $is_admin_panel);
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
                break;
            case ('sort_by_creation_asc'):
                $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'asc', $parent_directory, $search_text, $is_admin_panel);
                $sorting_asc_or_desc["Creation"] = ["desc" , 1];
                break;
            case ('sort_by_update_desc'):
                $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'updated_at', 'desc', $parent_directory, $search_text, $is_admin_panel);
                $sorting_asc_or_desc["Update"] = ["asc" , 1];
                break;
            case ('sort_by_update_asc'):
                $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'updated_at', 'asc', $parent_directory, $search_text, $is_admin_panel);
                $sorting_asc_or_desc["Update"] = ["desc" , 1];
                break;
            default:
                $directories_or_files = $this->sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'desc', $parent_directory, $search_text, $is_admin_panel);
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
        }     
        return ["directories_or_files" => $directories_or_files, "sorting_asc_or_desc" => $sorting_asc_or_desc];
    }
    
    //This function is required to simplify sort_for_albums_or_articles function.
    private function sort_by($search_mode_is_on, $items_amount_per_page, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, 
                             $parent_directory = null, $search_text = null, $is_admin_panel = null) {
        
        $directories_or_files = null;
        if ($search_mode_is_on == 1) {
            $directories_or_files = $this->sort_for_search($search_text, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, $is_admin_panel);
        } else {
            $directories_or_files = $this->normal_sort($items_amount_per_page, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, $parent_directory);
        }
        
        return $directories_or_files;
    }
    
    //This sort function is working when user is not searching anything, just opening pages to see the contents.
    private function normal_sort($items_amount_per_page, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, $parent_directory = null) {
        $for_albums_and_pictures = new AlbumsRepository();
        $for_folders_and_articles = new ArticlesRepository();
        
        $directories_or_files = null;
        
        switch ($what_to_sort) {
            case ('albums'):
                $directories_or_files = $for_albums_and_pictures->getAllLevelZeroAlbums($items_amount_per_page, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_albums'):
                $directories_or_files = $for_albums_and_pictures->getIncludedAlbums($parent_directory, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_pictures'):
                $directories_or_files = $for_albums_and_pictures->getIncludedPictures($parent_directory, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('folders'):
                $directories_or_files = $for_folders_and_articles->getAllLevelZeroFolders($items_amount_per_page, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_folders'):
                $directories_or_files = $for_folders_and_articles->getIncludedFolders($parent_directory, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_articles'):
                $directories_or_files = $for_folders_and_articles->getIncludedArticles($parent_directory, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
        }
        
        return $directories_or_files;
    }
    
    //This sort function is working when user is searching something.
    private function sort_for_search($search_text, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, $is_admin_panel) {
        
        $directories_or_files = null;
        
        switch ($what_to_sort) {
            case ('albums'):
                $for_albums_and_pictures = new AlbumsRepository();
                $directories_or_files = $for_albums_and_pictures->getAllAlbumsForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field, $asc_or_desc);
                break;
            case ('pictures'):
                $for_albums_and_pictures = new AlbumsRepository();
                $directories_or_files = $for_albums_and_pictures->getAllPicturesForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field, $asc_or_desc);
                break;
            case ('folders'):
                $for_folders_and_articles = new ArticlesRepository();
                $directories_or_files = $for_folders_and_articles->getAllFoldersForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field, $asc_or_desc);
                break;
            case ('articles'):
                $for_folders_and_articles = new ArticlesRepository();
                $directories_or_files = $for_folders_and_articles->getAllArticlesForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field, $asc_or_desc);
                break;
        }
        
        return $directories_or_files;
    }
}
