<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;

//We don't need the line below. May be we will need it in a future.
//use Illuminate\Http\Request;

class AdminAlbumsController extends Controller
{
    //
    protected $albums;
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(AlbumsRepository $albums){
        
        $this->albums = $albums;
        $this->current_page = 'Albums';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    public function index() {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        //On the line below we are fetching all articles from the database
        $albums = $this->albums->getAllAlbums($items_amount_per_page);
        
        return view('adminpages.adminalbums')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'albums' => $albums,
            'items_amount_per_page' => $items_amount_per_page
            ]);
    }
    
    public function showAlbum($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        
        $albums_and_pictures_full_info = $this->albums->getAlbum($keyword, $page, $items_amount_per_page);

        return view('adminpages.adminalbum')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $albums_and_pictures_full_info->head_title,
            'albumName' => $albums_and_pictures_full_info->album_name,           
            'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
            'albumParents' => $albums_and_pictures_full_info->albumParents,
            'pagination_info' => $albums_and_pictures_full_info->paginator_info,
            'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
            'items_amount_per_page' => $items_amount_per_page

            /*'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
            'number_of_pages' => $albums_and_pictures_full_info->number_of_pages,
            'current_page' => $albums_and_pictures_full_info->current_page,
            'previous_page' => $albums_and_pictures_full_info->previous_page,
            'next_page' => $albums_and_pictures_full_info->next_page,
            'items_amount_per_page' => $items_amount_per_page*/
            ]);
    }
}
