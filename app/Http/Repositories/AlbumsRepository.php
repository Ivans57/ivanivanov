<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use App\Http\Repositories\CommonRepository;
//The line below is required to show users which are added to some particular album.
use App\Http\Repositories\AdminUsersAddEditDeleteForDirectoryRepository;
use \App\Album;
use \App\Picture;
use \App\AlbumData;
use Illuminate\Support\Facades\Auth;


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
    public $pictureAmount;
    //This property is required for radio switch on view.
    //In case this property is 0, that radio switch is not required to display.
    public $albumAmount;
    //Two properties below are required to show correctly display_invisible element.
    public $allPicturesAmount;
    public $allAlbumsAmount;
    public $albumParents;
    public $albumNestingLevel;
    public $sorting_asc_or_desc;
    //It is better to keep this property here,
    //so in case of empty items array we don't need
    //to make an object.
    public $total_number_of_items;
    public $paginator_info;   
}

//This class is required for search.
class AlbumsOrPicturesWithPaginationInfo {
    public $all_items_count;
    //The propery below is required to display visibility checkbox properly.
    public $all_items_count_including_invisible;
    public $items_on_page;
    public $sorting_asc_or_desc;
    public $paginator_info;   
}

//This class is required for search.
class AlbumOrPictureForSearch {
    public $keyword;
    public $name;
    public $is_visible;
    public $created_at;
    public $updated_at;
    public $parent_keyword;
    //The last two properties are only for pictures
    public $path_to_file;
    public $file_name;
}


class AlbumsRepository {
       
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
    
    public function getAllAlbumsForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field = null, $asc_desc = null) {
        
        $albums = $this->getAllAlbumsForSearchFromDataBase($search_text, $including_invisible, $sort_by_field, $asc_desc);
        
        $albums_array = [];
     
        $for_path = new AlbumParentsRepository();
        
        foreach ($albums as $one_album) {            
            //First visibility check has been already proceeded when folders were taken from database.
            if ($is_admin_panel === 1 || $one_album->included_in_album_with_id === null) {           
                array_push($albums_array, $this->makeAlbumSearchArrayElement($one_album, $for_path));
            } else {
                $all_parents_are_visible = $this->checkAlbumParentVisibility(Album::where('id', '=', $one_album->included_in_album_with_id)->first());
                if ($all_parents_are_visible === 1) {          
                    array_push($albums_array, $this->makeAlbumSearchArrayElement($one_album, $for_path));
                }
            }
        }
        
        return $albums_array;
    }
    
    //The function below checks if all item's parents are visible.
    private function checkAlbumParentVisibility($parent) {
        $is_visible = 0;
        if ($parent->is_visible === 1 && $parent->included_in_album_with_id === null) {
            $is_visible = 1;
        } elseif($parent->is_visible === 1 && $parent->included_in_album_with_id !== null) {
            $is_visible = $this->checkAlbumParentVisibility(Album::where('id', '=', $parent->included_in_album_with_id)->first());
        }
        return $is_visible;
    }
    
    //This function takes an element, which is retrieved from database and converts it to an array element.
    private function makeAlbumSearchArrayElement($one_album, $for_path) {
        $album = new AlbumOrPictureForSearch();
           
        $path = ($one_album->included_in_album_with_id === null ) ? "0" : 
                            $for_path->get_full_directory_path($one_album->included_in_album_with_id, "", "name");
           
        $album->keyword = $one_album->keyword;
        $album->name = ($path === "0" ) ? " / ".$one_album->album_name : $path." / ".$one_album->album_name;
        $album->is_visible = $one_album->is_visible;
        $album->created_at = $one_album->created_at;
        $album->updated_at = $one_album->updated_at;
                     
        //$parent_keyword is required to make a property parent_keyword as a string.
        $parent_keyword = Album::where('id', $one_album->included_in_album_with_id)->first();
        $album->parent_keyword = ($parent_keyword === null) ? "0" : $parent_keyword->keyword;
        
        return $album;
    }
    
    //this function is required to simplify getAllAlbumsForSearch function.
    private function getAllAlbumsForSearchFromDataBase($search_text, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $albums = (Album::where('album_name', 'LIKE', '%'.$search_text.'%')->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        } else {
            $albums = (Album::where('album_name', 'LIKE', '%'.$search_text.'%')->where('is_visible', '=', 1)
                             ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        }
        
        return $albums;
    }
    
    public function getAllPicturesForSearch($search_text, $including_invisible, $is_admin_panel, $sort_by_field = null, $asc_desc = null) {
        
        $pictures = $this->getAllPicturesForSearchFromDataBase($search_text, $including_invisible, $sort_by_field, $asc_desc);
        
        $pictures_array = [];
        
        $for_path = new AlbumParentsRepository();
        
        foreach ($pictures as $one_picture) {
            //First visibility check has been already proceeded when folders were taken from database.
            if ($is_admin_panel === 1) {          
                array_push($pictures_array, $this->makePictureSearchArrayElement($one_picture, $for_path));
            } else {
                $all_parents_are_visible = $this->checkAlbumParentVisibility(Album::where('id', '=', $one_picture->included_in_album_with_id)->first());
                if ($all_parents_are_visible === 1) {          
                    array_push($pictures_array, $this->makePictureSearchArrayElement($one_picture, $for_path));
                }
            }
        }       
        return $pictures_array;
    }
    
    //This function takes an element, which is retrieved from database and converts it to an array element.
    private function makePictureSearchArrayElement($one_picture, $for_path) {
        $picture = new AlbumOrPictureForSearch();
           
        $path = ($one_picture->included_in_album_with_id === null ) ? "0" : 
                            $for_path->get_full_directory_path($one_picture->included_in_album_with_id, "", "name");
           
        $picture->keyword = $one_picture->keyword;
        $picture->name = ($path === "0" ) ? " / ".$one_picture->picture_caption : $path." / ".$one_picture->picture_caption;
        $picture->is_visible = $one_picture->is_visible;
        $picture->created_at = $one_picture->created_at;
        $picture->updated_at = $one_picture->updated_at;
           
        //The root path will look like like this, because we are getting pictures from storage folder via link in public folder.
        $picture->path_to_file = (App::isLocale('en') ? 'storage/albums/en' : 'storage/albums/ru').
                                 ((new AdminPicturesRepository())->getDirectoryPath($one_picture->included_in_album_with_id))."/";
        $picture->file_name = $one_picture->file_name;
           
        //$parent_keyword is required to make a property parent_keyword as a string.
        $parent_keyword = Album::where('id', $one_picture->included_in_album_with_id)->first();
        $picture->parent_keyword = ($parent_keyword === null) ? "0" : $parent_keyword->keyword;
        
        return $picture;
    }
    
    //this function is required to simplify getAllPicturesForSearch function.
    private function getAllPicturesForSearchFromDataBase($search_text, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $pictures = (Picture::where('picture_caption', 'LIKE', '%'.$search_text.'%')->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        } else {
            //The condition below is just temporary!
            $pictures = (Picture::where('picture_caption', 'LIKE', '%'.$search_text.'%')->where('is_visible', '=', 1)
                             ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        }
        
        return $pictures;
    }
    
    //This function returns all albums of some level elements except for 0 level elements.
    //There is a separate function for level zero elements, because they need to be paginated.
    //The null below for the last two arguments is just temporary!
    public function getIncludedAlbums($album, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $included_albums = Album::where('included_in_album_with_id', '=', $album->id)->orderBy($sort_by_field, $asc_desc)->get();
        } else {
            $included_albums = Album::where('included_in_album_with_id', '=', $album->id)
                        ->where('is_visible', '=', 1)->orderBy($sort_by_field, $asc_desc)->get();
        }
        return $included_albums;
    }
    
    public function getIncludedPictures($album, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $included_pictures = Picture::where('included_in_album_with_id', $album->id)->orderBy($sort_by_field, $asc_desc)->get();
        } else {
            $included_pictures = Picture::where('included_in_album_with_id', $album->id)->where('is_visible', '=', 1)
                    ->orderBy($sort_by_field, $asc_desc)->get();
        }
        return $included_pictures;
    }
     
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album
    public function showAlbumView($section, $page, $keyword, $items_amount_per_page, $main_links, 
                                    $is_admin_panel, $including_invisible, $sorting_mode = null, $albums_or_pictures_first = null) {
        
        $common_repository = new CommonRepository();
        //The condition below fixes a problem when user enters as a number of page some number less then 1
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $albums_and_pictures_full_info = $this->getAlbum($keyword, $page, $items_amount_per_page, 
                                                                $including_invisible, $sorting_mode, $albums_or_pictures_first);
            //We need to do the check below in case user enters a page number more than actual number of pages
            if ($page > $albums_and_pictures_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, $albums_and_pictures_full_info
                                            ->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, 
                                        $items_amount_per_page, $including_invisible, 
                                        $sorting_mode, $albums_or_pictures_first);
            }
        }
    }
    
    private function getAlbum($keyword, $page, $items_amount_per_page, $including_invisible, $sorting_mode = null, $albums_or_pictures_first = null) {
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.        
        //We are choosing the album we are working with at the current moment.
        $album = Album::where('keyword', $keyword)->first();
                   
        $albums_and_pictures_full = $this->get_included_albums_and_pictures_with_sort_info($including_invisible, $album, $items_amount_per_page, 
                                                                                            $sorting_mode, $albums_or_pictures_first);
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator.           
        return $this->get_included_albums_and_pictures_with_info($album, $albums_and_pictures_full, $page, $items_amount_per_page);
    }
    
    //This function adds some nacessary (e.g. pagination) information to an array of albums and pictures.
    private function get_included_albums_and_pictures_with_info($album, $albums_and_pictures_array_with_sort_info, $page, 
                                                                $items_amount_per_page) {
        //We need the object below which will contain an array of needed albums 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $albums_and_pictures_full_info = new AlbumAndPictureForViewFullInfoForPage();
        
        //The variables below are required to display view properly depending on whether the album has both pictures and albums included,
        //or has only pictures or albums included.
        $albums_and_pictures_full_info->pictureAmount = $albums_and_pictures_array_with_sort_info["picture_amount"];
        $albums_and_pictures_full_info->albumAmount = $albums_and_pictures_array_with_sort_info["album_amount"];
        
        //Two lines below are required to show correctly display_invisible element.
        $albums_and_pictures_full_info->allPicturesAmount = Album::where('included_in_album_with_id', '=', $album->id)->count();
        $albums_and_pictures_full_info->allAlbumsAmount = Picture::where('included_in_album_with_id', '=', $album->id)->count();
        
        $albums_and_pictures_full_info->sorting_asc_or_desc = $albums_and_pictures_array_with_sort_info['sorting_asc_or_desc'];
        
        $albums_and_pictures_full_info->albumNestingLevel = AlbumData::where('items_id', $album->id)->select('nesting_level')->firstOrFail()->nesting_level;
        
        $albums_and_pictures_full_info->albumParents = (($album->included_in_album_with_id) ? 
                                                        array_reverse($this->get_albums_parents_for_view($album->included_in_album_with_id)) : 0);
                    
        //The root path will look like like this, because we are getting pictures from storage folder via link in public folder.
        $albums_and_pictures_full_info->path_to_file = (App::isLocale('en') ? 
                                                        'storage/albums/en' : 'storage/albums/ru').((new AdminPicturesRepository())->getDirectoryPath($album->id))."/";
        
        $albums_and_pictures_full_info->head_title = $album->album_name;
        $albums_and_pictures_full_info->total_number_of_items = count($albums_and_pictures_array_with_sort_info['directories_or_files']);

        //The following information we can have only if we have at least one item in selected folder.
        if(count($albums_and_pictures_array_with_sort_info['directories_or_files']) > 0) {
            //The line below cuts all data into pages.
            //We can do it only if we have at least one item in the array of the full data.
            $albums_and_pictures_full_cut_into_pages = array_chunk($albums_and_pictures_array_with_sort_info['directories_or_files'], 
                                                                   $items_amount_per_page, false);
            $albums_and_pictures_full_info->paginator_info = (new CommonRepository())->get_paginator_info($page, 
                                                              $albums_and_pictures_full_cut_into_pages);
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
    private function get_included_albums_and_pictures_with_sort_info($including_invisible, $album, $items_amount_per_page, $sorting_mode, 
                                                                        $albums_or_pictures_first = null) {
        $for_sort = new CommonRepository();
        
        $included_albums = $for_sort->sort_for_albums_or_articles("0", $items_amount_per_page, $sorting_mode, $including_invisible, 
                                                                'included_albums', $album);        
        $included_pictures = $for_sort->sort_for_albums_or_articles("0", $items_amount_per_page, $sorting_mode, $including_invisible, 
                                                                'included_pictures', $album);
        
        //Here we are calling method which will merge all pictures and albums from selected album into one array.
        $included_albums_and_pictures_with_sort_info["directories_or_files"] = $this->
                    get_included_albums_and_pictures_array($included_albums["directories_or_files"], $included_pictures["directories_or_files"], 
                                                                                                                    $albums_or_pictures_first);
        
        $included_albums_and_pictures_with_sort_info["sorting_asc_or_desc"] = $included_albums["sorting_asc_or_desc"];
        
        //The variables below are required to display view properly depending on whether the album has both pictures and albums included,
        //or has only pictures or albums included.
        $included_albums_and_pictures_with_sort_info["picture_amount"] = count($included_pictures["directories_or_files"]);
        $included_albums_and_pictures_with_sort_info["album_amount"] = count($included_albums["directories_or_files"]);
        
        return $included_albums_and_pictures_with_sort_info;
    }
    
    //We need this function to make our own array which will contain all included
    //in some chosen album albums and pictures.
    private function get_included_albums_and_pictures_array($included_albums, $pictures, $albums_or_pictures_first = null) {        
        //We need to merge included albums and pictures to show them in selected album on the same page.
        if ($albums_or_pictures_first === "pictures_first") {
            $albums_and_pictures_full = array_merge($this->get_included_pictures($pictures, count($included_albums)), 
                                                 $this->get_included_albums($included_albums, count($included_albums)));
        } else {
            $albums_and_pictures_full = array_merge($this->get_included_albums($included_albums, count($included_albums)), 
                                                 $this->get_included_pictures($pictures, count($included_albums)));
        }
        
        return $albums_and_pictures_full;
    }
    
    //We need to make separate functions for albums and pictures parts of common array, because depending on user wish
    //on the website might be required to display albums before pictures or opposite.
    private function get_included_albums($included_albums, $included_albums_count) {
        $albums_and_pictures_full = array();
        
        for($i = 0; $i < $included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $included_albums[$i]->keyword;
            $albums_and_pictures_full[$i]->caption = $included_albums[$i]->album_name;
            $albums_and_pictures_full[$i]->createdAt = $included_albums[$i]->created_at;
            $albums_and_pictures_full[$i]->updatedAt = $included_albums[$i]->updated_at;
            $albums_and_pictures_full[$i]->isVisible = $included_albums[$i]->is_visible;
            $albums_and_pictures_full[$i]->type = 'album';   
        }     
        return $albums_and_pictures_full;
    }
    
    //We need to make separate functions for albums and pictures parts of common array, because depending on user wish
    //on the website might be required to display albums before pictures or opposite.
    private function get_included_pictures($pictures, $included_albums_count) {
        $albums_and_pictures_full = array();
        
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
    
    //We need the method below to clutter down showAlbumView method.
    private function get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, 
                                $items_amount_per_page, $including_invisible, $sorting_mode = null, $albums_or_pictures_first = null) {
        if ($is_admin_panel) {
            return $this->get_view_for_admin_panel($is_admin_panel, $keyword, $section, $main_links, $albums_and_pictures_full_info, 
                                                    $items_amount_per_page, $including_invisible, $sorting_mode, $albums_or_pictures_first);
        } else {
            return $this->get_view_for_website($is_admin_panel, $keyword, $section, $main_links, $albums_and_pictures_full_info, 
                                                    $items_amount_per_page, $sorting_mode, $albums_or_pictures_first);      
        }
    }
    
    //The function below is required to simplify get_view function.
    private function get_view_for_admin_panel($is_admin_panel, $keyword, $section, $main_links, $albums_and_pictures_full_info, 
                                              $items_amount_per_page, $including_invisible, $sorting_mode = null,
                                              $albums_or_pictures_first = null) {
        //AdminUsersAddEditDeleteForDirectoryRepository
        //I will retrieve all added users here in the most end to avoid any erros with passing parameters, because this is a 
        //common repossitory, there are also pagination and sorting functions, it is easy to get confused with all these parameters.
        $full_and_limited_access_users_names = (new AdminUsersAddEditDeleteForDirectoryRepository())
                                                ->get_full_and_limited_access_users_for_page($keyword);
        return view('adminpages.albums.adminalbum')->with([
                //The variable below is required to show current user which is logged in.
                'current_user_name' => Auth::user()->name,
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'pathToFile' => $albums_and_pictures_full_info->path_to_file,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'pictureAmount' => $albums_and_pictures_full_info->pictureAmount,
                'albumAmount' => $albums_and_pictures_full_info->albumAmount,
                //The variables below are required to display view properly depending on whether 
                //the album has both pictures and albums included, or has only pictures or albums included.
                'allPicturesAmount' => $albums_and_pictures_full_info->allPicturesAmount,
                'allAlbumsAmount' => $albums_and_pictures_full_info->allAlbumsAmount,
                'sorting_asc_or_desc' => $albums_and_pictures_full_info->sorting_asc_or_desc,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'nesting_level' => $albums_and_pictures_full_info->albumNestingLevel,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword,
                'sorting_mode' => ($sorting_mode) ? $sorting_mode : 'sort_by_creation_desc',
                //The variable below is required to keep previous 
                //sorting options in case all elements are invisible and user wants to make them visible again.
                'sorting_method_and_mode' => ($sorting_mode) ? $sorting_mode : '0',
                'albums_or_pictures_first' => ($albums_or_pictures_first) ? $albums_or_pictures_first : 'albums_first',
                'show_invisible' => $including_invisible == 1 ? 'all' : 'only_visible',
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel,
                'what_to_search' => 'albums',
                //The variable below is required for a check to show some specific content only for admin user.
                'user_role' => Auth::user()->role_and_status->role,
                //Four variables below are required to show added to Albums section users names on a page.
                'full_access_user_names' => $full_and_limited_access_users_names->full_access_users_names,
                'limited_access_user_names' => $full_and_limited_access_users_names->limited_access_users_names,
                'sizeof_full_access_users_names' => sizeof($full_and_limited_access_users_names->full_access_users_names),
                'sizeof_limited_access_users_names' => sizeof($full_and_limited_access_users_names->limited_access_users_names)
                ]);
    }
    
    //The function below is required to simplify get_view function.
    private function get_view_for_website($is_admin_panel, $keyword, $section, $main_links, $albums_and_pictures_full_info, 
                                            $items_amount_per_page, $sorting_mode = null, $albums_or_pictures_first = null) {       
        return view('pages.albums_and_pictures.album')->with([
                'main_links' => $main_links,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'pathToFile' => $albums_and_pictures_full_info->path_to_file,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'pictureAmount' => $albums_and_pictures_full_info->pictureAmount,
                'albumAmount' => $albums_and_pictures_full_info->albumAmount,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword,
                //Variable below needs to be corrected later.
                'sorting_mode' => $sorting_mode? $sorting_mode : 'sort_by_creation_desc',
                'directories_or_files_first' => ($albums_or_pictures_first) ? $albums_or_pictures_first : 0,
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel,
                'what_to_search' => 'albums'
                ]);
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
    
    //This function is used for search.
    public function getAlbumsOrPicturesFromSearch($search_text, $page, $items_amount_per_page, $what_to_search, $show_invisible, $is_admin_panel, $sorting_mode = null) {        
           
        $common = new CommonRepository();
        
        $items_with_pagination = new AlbumsOrPicturesWithPaginationInfo();
        
        //In the next line the data are getting extracted from the database and sorted.
        //The sixth parameter needs to pass as null to avoid confusion.
        $all_items = $common->sort_for_albums_or_articles(1, $items_amount_per_page, $sorting_mode, 
                                                          $show_invisible === "only_visible" ? 0 : 1, $what_to_search, null/*parent directory*/, $search_text, $is_admin_panel);
        
        $items_with_pagination->all_items_count = sizeof($all_items["directories_or_files"]);
        
        $items_with_pagination->all_items_count_including_invisible = ($what_to_search === "albums") ? Album::where('album_name', 'LIKE', '%'.$search_text.'%')->count() : 
                                                                       Picture::where('picture_caption', 'LIKE', '%'.$search_text.'%')->count();
        
        $items_with_pagination->sorting_asc_or_desc = $all_items["sorting_asc_or_desc"];
                
        //The following information we can have only if we have at least one item.
        if($items_with_pagination->all_items_count > 0) {
            //The line below cuts all data into pages.
            //We can do it only if we have at least one item in the array of the full data.
            $items_cut_into_pages = array_chunk($all_items["directories_or_files"], $items_amount_per_page, false);
            $items_with_pagination->paginator_info = $common->get_paginator_info($page, $items_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            $items_with_pagination->items_on_page = $items_with_pagination->paginator_info->number_of_pages >= $page ? 
                                                                $items_cut_into_pages[$page-1] : null;
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showAlbumView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder.
            $items_with_pagination->paginator_info = new Paginator();
            $items_with_pagination->paginator_info->number_of_pages = 1;
        }
        
        return $items_with_pagination;
    }
}
