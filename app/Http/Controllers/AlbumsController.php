<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;

        
class AlbumsController extends Controller {
    
    protected $albums;    
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
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
        $this->is_admin_panel = false;
    }
    
    public function index(){  
        
        //We need the line below to make main navigation links
        //We can't make global variable and initialize it in constructor because
        //localization won't be applied
        //Localiztion gets applied only if we call some certaion method from any controller
        //!Need to think is it possible still to apply localization in constructor!
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        $items_amount_per_page = 16;        
        $album_links = $this->albums->getAllAlbums($items_amount_per_page);

        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($album_links->currentPage() > $album_links->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $album_links->lastPage(), $this->is_admin_panel);
        } else {
            return view('pages.albums')->with([
                'headTitle' => $headTitle,
                'main_links' => $main_links,
                'album_links' => $album_links,
                'items_amount_per_page' => $items_amount_per_page
            ]);
        }
    }
            
    public function showAlbum($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 20;
        
        //We need to call the method below to clutter down current method in controller
        return $this->albums->showAlbumView(Str::lower($this->current_page), $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel);       
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
