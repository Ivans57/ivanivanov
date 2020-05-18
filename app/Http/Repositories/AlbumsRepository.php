<?php

namespace App\Http\Repositories;

use App\Http\Repositories\CommonRepository;
use App;


class AlbumLinkForView {
    public $keyWord;
    public $name;
}  
        
class AlbumAndPictureForView {
    public $keyWord;
    public $caption;
    public $type;
    public $fileExtension;
}

class AlbumAndPictureForViewFullInfoForPage {
    public $album_name;
    public $head_title;
    public $albumsAndPictures;
    public $albumParents;
    public $albumNestingLevel;
    //It is better to keep this property here,
    //so in case of empty items array we don't need
    //to make an object.
    public $total_number_of_items;
    public $paginator_info;   
}


class AlbumsRepository {
    
    public function getAllAlbums($items_amount_per_page, $including_invisible){
        
        if ($including_invisible) {
            $album_links = \App\Album::where('included_in_album_with_id', '=', NULL)->orderBy('created_at','DESC')->paginate($items_amount_per_page);
        } else {
            $album_links = \App\Album::where('included_in_album_with_id', '=', NULL)->where('is_visible', '=', 1)->orderBy('created_at','DESC')->paginate($items_amount_per_page);
        }
        return $album_links;
    }
       
    //We need this function to make a drop down list for Album addition in Admin Panel
    //This function accepts one argument, because when we have a drop down list
    //in edit window, we need to exclude being changed album and its parents
    //from that list, so user can't move the album into itself or its children.
    //The argument has default value NULL because the same function
    //is used for create method which can't give any argument to this function.
    public function getAllAlbumsList($albums_to_exclude_keyword = NULL){
        
        //First we need to filter out albums which caanot be parents du to their nesting level.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($albums_to_exclude_keyword);
        $albums = $this->get_all_albums_for_dp_list_from_query(NULL, $max_acceptable_nest_level, $albums_to_exclude_keyword);
      
        $albums_for_list = array();
        $albums_for_list[0] = '-';
        foreach ($albums as $album) {           
            $albums_for_list[$album->id] = $album->album_name;          
            //We need the variable below to add prefix spaces properly to each element
            //of the list, depeding on whether some particluar list of item is included
            //in some another item or no.
            $list_inclusion_level = 1;
            
            $all_included_albums = $this->get_all_included_albums($album->id, 
                    $list_inclusion_level, $max_acceptable_nest_level, $albums_to_exclude_keyword);
            if ($all_included_albums != NULL) {
                $albums_for_list = $albums_for_list + $all_included_albums;
            }
        }
        
        return $albums_for_list;
    }
    
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album
    public function showAlbumView($section, $page, $keyword, $items_amount_per_page, $main_links, $is_admin_panel, $including_invisible){
        
        $common_repository = new CommonRepository();
        //The condition below fixs a problem when user enters as a number of page some number less then 1
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $albums_and_pictures_full_info = $this->getAlbum($keyword, $page, $items_amount_per_page, $including_invisible);
            //We need to do the check below in case user enters a page number more than actual number of pages
            if ($page > $albums_and_pictures_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, $albums_and_pictures_full_info->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, $items_amount_per_page);
            }
        }
    }
       
    //We need this function to get all included albums in parent album.
    //This function accepts one argument, because when we have a drop down list
    //in edit window, we need to exclude being changed album and its parents
    //from that list, so user can't move the album into itself or its children.
    //The argument has default value NULL because the same function
    //is used for create method which can't give any argument to this function.
    private function get_all_included_albums($parent_album_id, 
            $list_inclusion_level, $max_acceptable_nest_level, $albums_to_exclude_keyword = NULL) {
                     
        $included_albums = $this->get_all_albums_for_dp_list_from_query($parent_album_id, $max_acceptable_nest_level, $albums_to_exclude_keyword);
                
        $included_albums_for_list = array();
        foreach ($included_albums as $included_album) {
           
            //The variable below is required to add prefix spaces to the element
            //of the list.
            $album_name_prefix = '';
            //We need a loop below to add prefix spaces to the elements of the list 
            for ($level_in_list = 0; $level_in_list < $list_inclusion_level; $level_in_list++) {
                $album_name_prefix = $album_name_prefix.'&nbsp; &nbsp;';
            }
            
            $included_albums_for_list[$included_album->id] = $album_name_prefix.$included_album->album_name;
            
            $all_included_albums = $this->get_all_included_albums($included_album->id, $list_inclusion_level + 1, 
                    $max_acceptable_nest_level, $albums_to_exclude_keyword);
            if ($all_included_albums != NULL) {
                /*foreach ($all_included_albums as $included_album) {
                   array_push($included_albums_for_list, $included_album);
                }*/
                $included_albums_for_list = $included_albums_for_list + $all_included_albums;
            }
        }
        
        return $included_albums_for_list;
    }
    
    //We need this function to shorten get_all_included_albums function.
    private function get_all_albums_for_dp_list_from_query($parent_album_id, $max_acceptable_nest_level, $albums_to_exclude_keyword = NULL) {
        
        if (App::isLocale('en')) {
        
            $included_albums = \App\Album::select('en_albums.id', 'en_albums.album_name')
                ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                ->where('en_albums.included_in_album_with_id', '=', $parent_album_id)
                ->where('en_albums.keyword', '!=', $albums_to_exclude_keyword)
                ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                ->orderBy('en_albums.created_at','DESC')->get();
    
        } else {
        
            $included_albums = \App\Album::select('ru_albums.id', 'ru_albums.album_name')
                ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                ->where('ru_albums.included_in_album_with_id', '=', $parent_album_id)
                ->where('ru_albums.keyword', '!=', $albums_to_exclude_keyword)
                ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                ->orderBy('ru_albums.created_at','DESC')->get();
        
        }
             
        return $included_albums;
    }
    
    //We need this function to shorten getAllAlbumsList function.
    private function get_max_acceptable_nest_level($albums_to_exclude_keyword = NULL) {
               
        $max_acceptable_nest_level = 7;
        
        if ($albums_to_exclude_keyword != NULL) {
            $items_id = \App\Album::where('keyword', $albums_to_exclude_keyword)->select('id')->firstOrFail();
            $items_nesting_level_and_children = \App\AlbumData::where('items_id', $items_id->id)
                    ->select('nesting_level', 'children')->firstOrFail();
            
            $items_children = json_decode($items_nesting_level_and_children->children, true);
            
            if (is_null($items_children)){
                $children_max_nest_level = $items_nesting_level_and_children->nesting_level;
            } else {
                $children_max_nest_level = $this->get_children_max_nest_level($items_children, $max_acceptable_nest_level);
            }
            
            $children_rel_max_nest_level = $children_max_nest_level - $items_nesting_level_and_children->nesting_level;
            $max_acceptable_nest_level = $max_acceptable_nest_level - $children_rel_max_nest_level;
        }
                   
        return $max_acceptable_nest_level;
    }
    
    //We need this function to shorten get_max_acceptable_nest_level function.
    private function get_children_max_nest_level($items_children_strings, $max_acceptable_nest_level) {
               
        //Converting string array to int array.
        $items_children = array_map('intval', $items_children_strings);
        $children_nest_levels = \App\AlbumData::whereIn('items_id', $items_children)
                    ->select('nesting_level')->get();
                
        $children_max_nest_level = 0;
                
            foreach ($children_nest_levels as $children_nest_level) {
                if ($children_nest_level->nesting_level > $children_max_nest_level && 
                        $children_nest_level->nesting_level != $max_acceptable_nest_level) {
                    $children_max_nest_level = $children_nest_level->nesting_level;
                } elseif ($children_nest_level->nesting_level > $children_max_nest_level && 
                        $children_nest_level->nesting_level == $max_acceptable_nest_level) {
                    $children_max_nest_level = $children_nest_level->nesting_level;
                    break;
                        }
            }
                   
        return $children_max_nest_level;
    }
    
    //We need the method below to clutter down showAlbumView method
    private function get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, $items_amount_per_page) {
        if ($is_admin_panel) {
            return view('adminpages.adminalbum')->with([
                'main_links' => $main_links->mainLinks,
                'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'albumName' => $albums_and_pictures_full_info->album_name,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'nesting_level' => $albums_and_pictures_full_info->albumNestingLevel,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword
                ]);
        } else {
            return view('pages.album')->with([
                'main_links' => $main_links,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'albumName' => $albums_and_pictures_full_info->album_name,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section
                ]);                   
        }
    }
    
    private function getAlbum($keyword, $page, $items_amount_per_page, $including_invisible){
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $album = \App\Album::where('keyword', $keyword)->firstOrFail();
        
        $nesting_level = \App\AlbumData::where('items_id', $album->id)->select('nesting_level')->firstOrFail();
            
        //Here we are calling method which will merge all pictures and folders from selected folder into one array
        if ($including_invisible) {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures(\App\Album::where('included_in_album_with_id', '=', $album->id)->orderBy('created_at','DESC')->get(), \App\Picture::where('album_id', $album->id)->orderBy('created_at','DESC')->get());
        } else {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures(\App\Album::where('included_in_album_with_id', '=', $album->id)->where('is_visible', '=', 1)->orderBy('created_at','DESC')->get(), \App\Picture::where('album_id', $album->id)->orderBy('created_at','DESC')->get());
        }
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator
        
        //We need the object below which will contain an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $albums_and_pictures_full_info = new AlbumAndPictureForViewFullInfoForPage();
        
        $albums_and_pictures_full_info->albumNestingLevel = $nesting_level->nesting_level;
        
        if($album->included_in_album_with_id === NULL) {
            $albums_and_pictures_full_info->albumParents = 0;
        }
        else {
            $albums_and_pictures_full_info->albumParents = array_reverse($this->get_albums_parents_for_view($album->included_in_album_with_id));
        }
        
        $albums_and_pictures_full_info->album_name = $album->keyword;
        $albums_and_pictures_full_info->head_title = $album->album_name;
        $albums_and_pictures_full_info->total_number_of_items = count($albums_and_pictures_full);

        //The following information we can have only if we have at least one item in selected folder
        if(count($albums_and_pictures_full) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $albums_and_pictures_full_cut_into_pages = array_chunk($albums_and_pictures_full, $items_amount_per_page, false);
            $albums_and_pictures_full_info->paginator_info = (new CommonRepository())->get_paginator_info($page, $albums_and_pictures_full_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            if ($albums_and_pictures_full_info->paginator_info->number_of_pages >= $page) {
                //The line below selects the page we need, as computer counts from 0, we need to subtract 1
                $albums_and_pictures_full_info->albumsAndPictures = $albums_and_pictures_full_cut_into_pages[$page-1];
            }           
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showAlbumView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $albums_and_pictures_full_info->paginator_info = new Paginator();
            $albums_and_pictures_full_info->paginator_info->number_of_pages = 1;
        }
               
        return $albums_and_pictures_full_info;
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //album keyword or editing existing.
    public function get_all_albums_keywords() {
        
        $all_albums_keywords = \App\Album::all('keyword');
        
        $albums_keywords_array = array();
        
        foreach ($all_albums_keywords as $album_keyword) {
            array_push($albums_keywords_array, $album_keyword->keyword);
        }
        
        return $albums_keywords_array;
        
    }

    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_albums_and_pictures($included_albums, $pictures){
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $albums_and_pictures_full = array();       
        $included_albums_count = count($included_albums);
        
        for($i = 0; $i < $included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $included_albums[$i]->keyword;
            $albums_and_pictures_full[$i]->caption = $included_albums[$i]->album_name;
            $albums_and_pictures_full[$i]->type = 'album';
            //$albums_and_pictures_full[$i]->fileExtension = 0;   
        }
                        
        for($i = $included_albums_count; $i < count($pictures)+$included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $pictures[$i-$included_albums_count]->keyword;
            $albums_and_pictures_full[$i]->caption = $pictures[$i-$included_albums_count]->picture_caption;
            $albums_and_pictures_full[$i]->type = 'picture';
            $albums_and_pictures_full[$i]->fileExtension = $pictures[$i-$included_albums_count]->file_extension;
        }
        
        return $albums_and_pictures_full;
    }
    
    private function get_albums_parents_for_view($id) {
        
        $parent_album = \App\Album::where('id', $id)->firstOrFail();
        
        $parent_album_for_view = new AlbumLinkForView();
        
        $parent_album_for_view->keyWord = $parent_album->keyword;
        $parent_album_for_view->name = $parent_album->album_name;
        
        $parent_albums_for_view = array();
        
        $parent_albums_for_view[] = $parent_album_for_view;
        
        if ($parent_album->included_in_album_with_id === NULL) {
            return $parent_albums_for_view;
        }
        else {
            $albums_parents_for_view = $this->get_albums_parents_for_view($parent_album->included_in_album_with_id);
            foreach ($albums_parents_for_view as $albums_parent_for_view) {
                $parent_albums_for_view[] = $albums_parent_for_view;
            }
            return $parent_albums_for_view;
        }
    }
    
}
