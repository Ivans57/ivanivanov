<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use App\Http\Repositories\CommonRepository;
use \App\Album;
use \App\Picture;
use \App\AlbumData;


class AlbumLinkForView {
    public $keyWord;
    public $name;
}  
        
class AlbumAndPictureForView {
    public $keyWord;
    public $caption;
    public $createdAt;
    public $updatedAt;
    public $isVisible;
    public $type;
    public $fileName;
}

class AlbumAndPictureForViewFullInfoForPage {
    public $path_to_file;
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
       
    //Apparently this method has to become private later!
    //The null below for the last two arguments is just temporary!
    public function getAllLevelZeroAlbums($items_amount_per_page, $including_invisible, $sort_by_field = null, $asc_desc = null) {      
        if ($including_invisible) {
            $album_links = Album::where('included_in_album_with_id', '=', NULL)
                           ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                           ($asc_desc) ? $asc_desc : 'desc')
                           ->paginate($items_amount_per_page);
        } else {
            $album_links = Album::where('included_in_album_with_id', '=', NULL)->where('is_visible', '=', 1)
                           ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                           ($asc_desc) ? $asc_desc : 'desc')->paginate($items_amount_per_page);
        }
        return $album_links;
    }
    
    //The method below is to sort albums and pictures in different modes.
    public function sort($items_amount_per_page, $sorting_mode, $including_invisible, $what_to_sort, $parent_album = null) {
        //This array is required to show sorting arrows properly.
        $sorting_asc_or_desc = ["Name" => ["desc" , 0], "Creation" => ["desc" , 0], "Update" => ["desc" , 0],];
        
        $albums_or_pictures = null;
        
        switch ($sorting_mode) {
            case ('albums_sort_by_name_desc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    (($what_to_sort === 'included_pictures') ? 'picture_caption' : 'album_name'), 'desc', $parent_album);
                $sorting_asc_or_desc["Name"] = ["asc" , 1];
                break;
            case ('albums_sort_by_name_asc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    (($what_to_sort === 'included_pictures') ? 'picture_caption' : 'album_name'), 'asc', $parent_album);
                $sorting_asc_or_desc["Name"] = ["desc" , 1];
                break;
            case ('albums_sort_by_creation_desc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'desc', $parent_album);
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
                break;
            case ('albums_sort_by_creation_asc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'asc', $parent_album);
                $sorting_asc_or_desc["Creation"] = ["desc" , 1];
                break;
            case ('albums_sort_by_update_desc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'updated_at', 'desc', $parent_album);
                $sorting_asc_or_desc["Update"] = ["asc" , 1];
                break;
            case ('albums_sort_by_update_asc'):
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'updated_at', 'asc', $parent_album);
                $sorting_asc_or_desc["Update"] = ["desc" , 1];
                break;
            default:
                $albums_or_pictures = $this->sort_by($items_amount_per_page, $including_invisible, $what_to_sort, 
                                    'created_at', 'desc', $parent_album);
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
        }     
        return ["albums_or_pictures" => $albums_or_pictures, "sorting_asc_or_desc" => $sorting_asc_or_desc];
    }
    
    //This function is required to simplify sort function.
    public function sort_by($items_amount_per_page, $including_invisible, $what_to_sort, $sort_by_field, $asc_or_desc, $parent_album = null) {
        $albums_or_pictures = null;
        switch ($what_to_sort) {
            case ('albums'):
                $albums_or_pictures = $this->getAllLevelZeroAlbums($items_amount_per_page, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_albums'):
                $albums_or_pictures = $this->getIncludedAlbums($parent_album, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
            case ('included_pictures'):
                $albums_or_pictures = $this->getIncludedPictures($parent_album, $including_invisible, $sort_by_field, $asc_or_desc);
                break;
        }
        return $albums_or_pictures;
    }
    
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album
    public function showAlbumView($section, $page, $keyword, $items_amount_per_page, $main_links, $is_admin_panel, $including_invisible) {
        
        $common_repository = new CommonRepository();
        //The condition below fixs a problem when user enters as a number of page some number less then 1
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $albums_and_pictures_full_info = $this->getAlbum($keyword, $page, $items_amount_per_page, $including_invisible);
            //We need to do the check below in case user enters a page number more than actual number of pages
            if ($page > $albums_and_pictures_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, $albums_and_pictures_full_info
                                            ->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, 
                                        $items_amount_per_page);
            }
        }
    }
    
    private function getAlbum($keyword, $page, $items_amount_per_page, $including_invisible) {
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment.
        $album = Album::where('keyword', $keyword)->firstOrFail();
                   
        $albums_and_pictures_full = $this->get_included_albums_and_pictures($including_invisible, $album->id);
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator.           
        return $this->get_included_albums_and_pictures_with_info($album, $albums_and_pictures_full, $page, $items_amount_per_page);
    }
    
    //This function adds some nacessary (e.g. pagination) information to an array of albums and pictures.
    private function get_included_albums_and_pictures_with_info($album, $albums_and_pictures_array, $page, $items_amount_per_page) {
        //We need the object below which will contain an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $albums_and_pictures_full_info = new AlbumAndPictureForViewFullInfoForPage();
        
        $albums_and_pictures_full_info->albumNestingLevel = AlbumData::where('items_id', $album->id)->select('nesting_level')->firstOrFail()->nesting_level;
        
        $albums_and_pictures_full_info->albumParents = (($album->included_in_album_with_id) ? 
                                                        array_reverse($this->get_albums_parents_for_view($album->included_in_album_with_id)) : 0);
                    
        //The root path will look like like this, because we are getting pictures from storage folder via link in public folder.
        $albums_and_pictures_full_info->path_to_file = (App::isLocale('en') ? 
                                                        'storage/albums/en' : 'storage/albums/ru').((new AdminPicturesRepository())->getDirectoryPath($album->id))."/";
        
        $albums_and_pictures_full_info->head_title = $album->album_name;
        $albums_and_pictures_full_info->total_number_of_items = count($albums_and_pictures_array);

        //The following information we can have only if we have at least one item in selected folder
        if(count($albums_and_pictures_array) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $albums_and_pictures_full_cut_into_pages = array_chunk($albums_and_pictures_array, $items_amount_per_page, false);
            $albums_and_pictures_full_info->paginator_info = (new CommonRepository())->get_paginator_info($page, $albums_and_pictures_full_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            $albums_and_pictures_full_info->albumsAndPictures = $albums_and_pictures_full_info->paginator_info->number_of_pages >= $page ? 
                                                                $albums_and_pictures_full_cut_into_pages[$page-1] : null;
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
    
    //We need this function to shorten getAlbum function.
    private function get_included_albums_and_pictures($including_invisible, $album_id) {
        //Here we are calling method which will merge all pictures and albums from selected album into one array
        if ($including_invisible) {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures_array(Album::where('included_in_album_with_id', '=', 
                                        $album_id)->orderBy('created_at','DESC')->get(), Picture::where('included_in_album_with_id', $album_id)
                                        ->orderBy('created_at','DESC')->get());
        } else {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures_array(Album::where('included_in_album_with_id', '=', 
                                        $album_id)->where('is_visible', '=', 1)->orderBy('created_at','DESC')->get(), 
                                        Picture::where('included_in_album_with_id', $album_id)->where('is_visible', '=', 1)
                                        ->orderBy('created_at','DESC')->get());
        }
        return $albums_and_pictures_full;
    }
    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_albums_and_pictures_array($included_albums, $pictures) {
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $albums_and_pictures_full = array();       
        $included_albums_count = count($included_albums);
        
        for($i = 0; $i < $included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $included_albums[$i]->keyword;
            $albums_and_pictures_full[$i]->caption = $included_albums[$i]->album_name;
            $albums_and_pictures_full[$i]->createdAt = $included_albums[$i]->created_at;
            $albums_and_pictures_full[$i]->updatedAt = $included_albums[$i]->updated_at;
            $albums_and_pictures_full[$i]->isVisible = $included_albums[$i]->is_visible;
            $albums_and_pictures_full[$i]->type = 'album';   
        }
                        
        for($i = $included_albums_count; $i < count($pictures)+$included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $pictures[$i-$included_albums_count]->keyword;
            $albums_and_pictures_full[$i]->caption = $pictures[$i-$included_albums_count]->picture_caption;
            $albums_and_pictures_full[$i]->createdAt = $pictures[$i-$included_albums_count]->created_at;
            $albums_and_pictures_full[$i]->updatedAt = $pictures[$i-$included_albums_count]->updated_at;
            $albums_and_pictures_full[$i]->isVisible = $pictures[$i-$included_albums_count]->is_visible;
            $albums_and_pictures_full[$i]->type = 'picture';
            $albums_and_pictures_full[$i]->fileName = $pictures[$i-$included_albums_count]->file_name;
        }
        
        return $albums_and_pictures_full;
    }
    
    private function get_albums_parents_for_view($id) {
        
        $parent_album = Album::where('id', $id)->firstOrFail();
        
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
    
    //We need the method below to clutter down showAlbumView method
    private function get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, 
                                $items_amount_per_page, $sorting_mode = null) {
        if ($is_admin_panel) {
            return view('adminpages.adminalbum')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'pathToFile' => $albums_and_pictures_full_info->path_to_file,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'nesting_level' => $albums_and_pictures_full_info->albumNestingLevel,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword,
                'sorting_mode' => $sorting_mode,
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel
                ]);
        } else {
            return view('pages.album')->with([
                'main_links' => $main_links,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'pathToFile' => $albums_and_pictures_full_info->path_to_file,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword,
                //Variable below needs to be corrected later.
                'sorting_mode' => null,
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel
                ]);                   
        }
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //album keyword or editing existing.
    public function get_all_albums_keywords() {
        
        $all_albums_keywords = Album::all('keyword');       
        $albums_keywords_array = array();
        
        foreach ($all_albums_keywords as $album_keyword) {
            array_push($albums_keywords_array, $album_keyword->keyword);
        }    
        return $albums_keywords_array;   
    }
}
