<?php

namespace App\Http\Repositories;
use  App\Http\Repositories\CommonRepository;

class AlbumLinkForView {
    public $keyWord;
    public $albumName;
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
    //It is better to keep this property here,
    //so in case of empty items array we don't need
    //to make an object.
    public $total_number_of_items;
    public $paginator_info;   
}

class AlbumsRepository {
    
    public function getAllAlbums(){
        $album_links = \App\Album::where('included_in_album_with_id', '=', NULL)->where('is_visible', '=', 1)->paginate(16);
        
        return $album_links;
    }
    
    //need to make methods for this method
    public function getAlbum($keyword, $page, $items_amount_per_page){
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $album = \App\Album::where('keyword', $keyword)->first();    
        
        //Here we are calling method which will merge all pictures and folders from selected folder into one array
        $albums_and_pictures_full = $this->get_included_albums_and_pictures(\App\Album::where('included_in_album_with_id', '=', $album->id)->get(), \App\Picture::where('album_id', $album->id)->get());
        
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator
        
        //We need the object below which will contatin an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $albums_and_pictures_full_info = new AlbumAndPictureForViewFullInfoForPage();
        
        if($album->included_in_album_with_id === NULL) {
            $albums_and_pictures_full_info->albumParents = 0;
        }
        else {
            $albums_and_pictures_full_info->albumParents = array_reverse($this->get_albums_parents_for_view($album->included_in_album_with_id));
        }
        
        $albums_and_pictures_full_info->album_name = $album->keyword;
        $albums_and_pictures_full_info->head_title = $album->album_name;
        $albums_and_pictures_full_info->total_number_of_items = count($albums_and_pictures_full);
        //Paginator information we can have only if we have more then one item in selected folder
        if(count($albums_and_pictures_full) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $albums_and_pictures_full_cut_into_pages = array_chunk($albums_and_pictures_full, $items_amount_per_page, false);
            //The line below selects the page we need, as computer counts from 0, we need to subtract 1
            $albums_and_pictures_full_info->albumsAndPictures = $albums_and_pictures_full_cut_into_pages[$page-1];
            $common_repository = new CommonRepository();
            $albums_and_pictures_full_info->paginator_info = $common_repository->get_paginator_info($page, $albums_and_pictures_full_cut_into_pages);
        }
               
        return $albums_and_pictures_full_info;
    }
    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_albums_and_pictures($included_albums, $pictures){
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $albums_and_pictures_full = array();       
        $included_albums_count = count($included_albums);
        
        for($i = 0; $i < $included_albums_count; $i++) {
            if ($included_albums[$i]->is_visible) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $included_albums[$i]->keyword;
            $albums_and_pictures_full[$i]->caption = $included_albums[$i]->album_name;
            $albums_and_pictures_full[$i]->type = 'album';
            //$albums_and_pictures_full[$i]->fileExtension = 0;
            }
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
        
        $parent_album = \App\Album::where('id', $id)->first();
        
        $parent_album_for_view = new AlbumLinkForView();
        
        $parent_album_for_view->keyWord = $parent_album->keyword;
        $parent_album_for_view->albumName = $parent_album->album_name;
        
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
