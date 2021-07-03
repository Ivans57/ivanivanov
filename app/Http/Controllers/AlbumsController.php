<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;
use \App\Album;
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;

        
class AlbumsController extends Controller {
    
    protected $albums;    
    protected $current_page;
    protected $common;
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
        $this->common = new CommonRepository();
        $this->is_admin_panel = false;
    }
    
    public function index($sorting_mode = null){  
        
        //We need the line below to make main navigation links
        //We can't make global variable and initialize it in constructor because
        //localization won't be applied
        //Localiztion gets applied only if we call some certaion method from any controller
        //!Need to think is it possible still to apply localization in constructor!
        $main_links = $this->common->get_main_website_links($this->current_page);
        
        $items_amount_per_page = 16;        

        //In the next line the data are getting extracted from the database and sorted.
        //The fourth parameter is 'folders', because currently we are working with level 0 folders.
        $sorting_data = $this->common->sort_for_albums_or_articles(0, $items_amount_per_page, $sorting_mode, 0, 'albums');
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page.
        //To avoid indefinite looping need to check whether a section has at least one element.
        if ($sorting_data["directories_or_files"][0] && 
                ($sorting_data["directories_or_files"]->currentPage() > $sorting_data["directories_or_files"]->lastPage())) {
                    return $this->common->redirect_to_last_page_one_entity(Str::lower($this->current_page), 
                    $sorting_data["directories_or_files"]->lastPage(), $this->is_admin_panel);
        } else {
            return view('pages.albums_and_pictures.albums')->with([
                'headTitle' => __('keywords.'.$this->current_page),
                'main_links' => $main_links,
                'album_links' => $sorting_data["directories_or_files"],
                'section' => Str::lower($this->current_page),
                'sorting_mode' => ($sorting_mode) ? $sorting_mode : 'sort_by_creation_desc',
                'items_amount_per_page' => $items_amount_per_page,
                //The line below is required to show correctly display_invisible elements.
                'all_albums_count' => Album::where('included_in_album_with_id', '=', null)->count(),
                //The variable below is required for sort to indicate which function to call index or search.
                'search_is_on' => "0",
                'what_to_search' => 'albums'
            ]);
        }
    }
            
    public function show($keyword, $page, $sorting_mode = null, $albums_or_pictures_first = null) {
        
        $main_links = $this->common->get_main_website_links($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 20;
        
        //We need to call the method below to clutter down current method in controller
        return $this->albums->showAlbumView(Str::lower($this->current_page), 
                $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel, 0, $sorting_mode, $albums_or_pictures_first);
    }
    
    public function searchAlbumOrPicture(Request $request) {
        $items_amount_per_page = 14;
        
        $albums_or_pictures_with_info = $this->albums->getAlbumsOrPicturesFromSearch($request->input('find_by_name'), $request->input('page_number'), 
                                                                $items_amount_per_page, $request->input('what_to_search'), "only_visible"/*$show_invisible*/, 
                                                                0/*$is_admin_panel*/, $request->input('sorting_mode'));
               
        $albums_or_pictures = $albums_or_pictures_with_info->items_on_page;
        $all_items_amount = $albums_or_pictures_with_info->all_items_count;
        //The variable below is required to display bisibility checkbox properly.
        $pagination_info = $albums_or_pictures_with_info->paginator_info;
        //The variable below is required for sort to indicate which function to call index or search.
        $search_is_on = "1";
        $sorting_method_and_mode = ($request->input('sorting_mode') === null) ? "0" : $request->input('sorting_mode');
        $section = "albums";
        $what_to_search = $request->input('what_to_search');      
        $path = "";       
        $title = view('pages.albums_and_pictures.album_search_title')->render();       
        
        $content = view('pages.albums_and_pictures.albums_searchcontent', 
                compact("albums_or_pictures", "all_items_amount", "items_amount_per_page", "pagination_info", "search_is_on", "sorting_method_and_mode", 
                        "section", "what_to_search"))->render();
        
        
        return response()->json(compact('path', 'title', 'content'));
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
