<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;

use Illuminate\Http\Request;

        
class AlbumsController extends Controller {
    
    protected $albums;    
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in cotructor
    public function __construct(AlbumsRepository $albums){
        //We will get albums and pictures using special method from some certain
        //repository
        //The line below is making an object of required repository class
        $this->albums = $albums;
        $this->current_page = 'Albums';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();
    }
    
    public function index(){  
        
        //We need the line below to make main navigation links
        //We can't make global variable and initialize it in constructor because
        //localization won't be applied
        //Localiztion gets applied only if we call some certaion method from any controller
        //!Need to think is it possible still to apply localization in constructor!
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);     
        $album_links = $this->albums->getAllAlbums();
        
        
        //return $album_links;
        return view('pages.albums')->with([
            'headTitle' => $headTitle,
            'main_links' => $main_links,
            'album_links' => $album_links,
            ]);
        
    }
    
    public function showAlbum($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);        
        $albums_and_pictures_full_info = $this->albums->getAlbum($keyword, $page);
        
        return view('pages.album')->with([
            'main_links' => $main_links,
            'headTitle' => $albums_and_pictures_full_info->head_title,
            'albumName' => $albums_and_pictures_full_info->album_name,           
            'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
            'albumParents' => $albums_and_pictures_full_info->albumParents,
            'albums_and_pictures_total_number' => $albums_and_pictures_full_info->albums_and_pictures_total_number,
            'albums_and_pictures_number_of_pages' => $albums_and_pictures_full_info->albums_and_pictures_number_of_pages,
            'albums_and_pictures_current_page' => $albums_and_pictures_full_info->albums_and_pictures_current_page,
            'albums_and_pictures_previous_page' => $albums_and_pictures_full_info->albums_and_pictures_previous_page,
            'albums_and_pictures_next_page' => $albums_and_pictures_full_info->albums_and_pictures_next_page
            ]);
               
    }
    
    public function testik(Request $request){
        
        /*$id = $_GET['id'];*/
        
        //$test = $request->getContent();
                     
        $greeting = 'Hello ';
        
        $name = $request->input('name');
        
        //$name = $_GET['name'];
        
        $full_greeting = $greeting.$name;

        //return $test_for_return;
        
        //return response()->json(['response' => 'This is get method']);
        return response()->json(['response' => $full_greeting]);
        
        /*if(Request::ajax()){
            return 'getRequest has loaded completely.';
        }*/
        
    }
    
}
