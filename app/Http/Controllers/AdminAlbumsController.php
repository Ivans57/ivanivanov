<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AdminAlbumsRepository;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters low case.
use Illuminate\Support\Str;
use App\Http\Requests\CreateEditAlbumRequest;
use App\Album;


class AdminAlbumsController extends Controller
{
    protected $albums;
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(AdminAlbumsRepository $albums) {
        
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
        //For some lines e.g. two lines below which are getting repeated need to apply inheritance mechanism!
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;       
        //On the line below we are fetching all articles from the database
        $albums = $this->albums->getAllAlbums($items_amount_per_page, 1);
      
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page.
        //To avoid indefinite looping need to check whether a section has at least one element.
        if ($albums[0] && ($albums->currentPage() > $albums->lastPage())) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $albums->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.adminalbums')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'albums' => $albums,
            'items_amount_per_page' => $items_amount_per_page,
            //If we open just a root path of Albums we won't have any parent keyword,
            //to avoid an exception we will assign it 0.
            'parent_keyword' => "0",
            ]);
        }    
    }
    
    public function show($keyword, $page) {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        
        //We need to call the method below to clutter down current method in controller
        return $this->albums->showAlbumView(Str::lower($this->current_page), $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel, 1);
    }
    
    public function create($parent_keyword) {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);            
        if ($parent_keyword != "0") {
            $parent_info = \App\Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
                      
        return view('adminpages.directory.create_and_edit_directory')->with([
            'headTitle' => $headTitle,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'create',
            //The line below is required for form path.
            'section' => 'albums',            
            ]);
    }
    
    
    public function store(CreateEditAlbumRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);         
        $this->albums->store($request);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function edit($keyword, $parent_keyword) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error. 
        $headTitle= __('keywords.'.$this->current_page);                    
        if ($parent_keyword != "0") {
            $parent_info = \App\Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
        
        return view('adminpages.directory.create_and_edit_directory')->with([
            'headTitle' => $headTitle,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'edit',
            'edited_directory' => Album::where('keyword', '=', $keyword)->firstOrFail(),
            //The line below is required for form path.
            'section' => 'albums',
            ]);
        
    }
    
    //Possibly, field old_keyword is not needed as we are passing 
    //$keyword variable as the first argument and it is mentioned in routes file.
    public function update($keyword, CreateEditAlbumRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);       
        $this->albums->update($keyword, $request);

        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function delete($keyword) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
            
        return view('adminpages.directory.delete_directory')->with([
            'headTitle' => $headTitle,
            'keyword' => $keyword,
            //The line below is required for form path.
            'section' => 'albums',
            ]);
    }
    
    public function destroy($keyword) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);      
        $this->albums->destroy($keyword);
        
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
}