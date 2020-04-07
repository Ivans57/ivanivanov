<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters low case.
use Illuminate\Support\Str;
use Carbon\Carbon;
//use Request;
use App\Http\Requests\CreateEditAlbumRequest;
use App\Album;

class AdminAlbumsController extends Controller
{
    //
    protected $albums;
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
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
        $this->is_admin_panel = true;
    }
    
    public function index() {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;       
        //On the line below we are fetching all articles from the database
        $albums = $this->albums->getAllAlbums($items_amount_per_page);
      
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($albums->currentPage() > $albums->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $albums->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.adminalbums')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'albums' => $albums,
            'items_amount_per_page' => $items_amount_per_page
            ]);
        }    
    }
    
    public function showAlbum($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        
        //We need to call the method below to clutter down current method in controller
        return $this->albums->showAlbumView(Str::lower($this->current_page), $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel);
    }
    
    public function create() {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        $albums = $this->albums->getAllAlbumsList();
        
        //We need a list with all keywords to check whether the new keyword is unique
        //$keywords_full_data = Keyword::select('keyword')->get();
        
        //There are lots of another data in the variable $keywords_full_data
        //Below I am extracting only required data and pushing it in the new array $keywords
        //$keywords = array();
        
        //foreach($keywords_full_data as $keyword_full_data) {
            //array_push($keywords, $keyword_full_data->keyword);
        //}
        
        //As there is not possible to pass any arrays to the view or javascript,
        //we need to convert the $keywords array to json
        //$keywords_json = json_encode($keywords);
        
        //We are going to use one view for create and edit
        //thats why we will nedd kind of indicator to know which option do we use
        //create or edit.
        //$create_or_edit = 'create';
        
        //for example
        //$user_types = UserTypes::pluck('name', 'id');
        
        return view('adminpages.create_and_edit_album')->with([
            'headTitle' => $headTitle,
            'albums' => $albums
            //'keywords' => $keywords_json,
            //'create_or_edit' => $create_or_edit
            ]);
    }
    
    
    public function store(CreateEditAlbumRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error.
        $headTitle= __('keywords.'.$this->current_page);
        
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent album
        //then parent album id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record
        if ($input['included_in_album_with_id'] == 0){
            $input['included_in_album_with_id'] = NULL;
        }      
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        Album::create($input);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
}