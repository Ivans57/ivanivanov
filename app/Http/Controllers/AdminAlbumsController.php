<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AlbumsRepository;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters low case.
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        //For some lines e.g. two lines below which are getting repeated need to apply inheritance mechanism!
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;       
        //On the line below we are fetching all articles from the database
        $albums = $this->albums->getAllAlbums($items_amount_per_page, 1);
      
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
            'items_amount_per_page' => $items_amount_per_page,
            //If we open just a root path of Albums we won't have any parent keyword,
            //to avoid an exception we will assign it 0.
            'parent_keyword' => "0",
            ]);
        }    
    }
    
    public function show($keyword, $page){
        
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
        
        //We need this variable to find out which mode are we using Create or Edit
        //and then to open a view accordingly with a chosen mode
        $create_or_edit = 'create';
        
        //Need to remove the line below.
        $albums = $this->albums->getAllAlbumsList();
        
        if ($parent_keyword != "0") {
            $parent_info = \App\Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
                      
        return view('adminpages.create_and_edit_album')->with([
            'headTitle' => $headTitle,
            //Need to remove the line below.
            'albums' => $albums,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            'create_or_edit' => $create_or_edit,
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
        
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
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
    
    public function edit($keyword, $parent_keyword) {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need this variable to find out which mode are we using Create or Edit
        //and then to open a view accordingly with a chosen mode
        $create_or_edit = 'edit';
        
        //Need to remove the line below.
        $albums = $this->albums->getAllAlbumsList($keyword);
        
        $edited_album = Album::where('keyword', '=', $keyword)->firstOrFail();
        
        if ($parent_keyword != "0") {
            $parent_info = \App\Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
        
        return view('adminpages.create_and_edit_album')->with([
            'headTitle' => $headTitle,
            //Need to remove the line below.
            'albums' => $albums,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            'create_or_edit' => $create_or_edit,
            'edited_album' => $edited_album,
            ]);
        
    }
    
    public function update($keyword, CreateEditAlbumRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error.
        $headTitle= __('keywords.'.$this->current_page);
        
        $edited_album = Album::where('keyword', '=', $keyword)->firstOrFail();
        
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent album
        //then parent album id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record
        if ($input['included_in_album_with_id'] == 0){
            $input['included_in_album_with_id'] = NULL;
        }      
 
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }
        
        $input['updated_at'] = Carbon::now();
        
        $edited_album->update($input);
        //Album::update($input);
        
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
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error.
        $headTitle= __('keywords.'.$this->current_page);
        
        //return 'Delete '.$keyword.'?';
        return view('adminpages.delete_album')->with([
            'headTitle' => $headTitle,
            'keyword' => $keyword
            ]);
    }
    
    public function destroy($keyword) {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error.
        $headTitle= __('keywords.'.$this->current_page);
        
        //return 'Delete '.$keyword.'?';
        Album::where('keyword', '=', $keyword)->delete();
        
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function findParents(Request $request){
                     
        $parents = $this->albums->getParents($request->input('localization'), $request->input('page'), 
                $request->input('parent_search'), $request->input('keyword'));
                   
        if (count($parents->parentsDataArray) > 0) {              
            return response()->json(['albums_data' => $parents->parentsDataArray, 'pagination_info' => $parents->paginationInfo]);
        } else {
            //Here we need to override the following property, because in case user doesn't enter anything in search
            //system supposed to return nothing instead of everything and there shouldn't be any pages.
            $parents->paginationInfo->nextPage = null;
            return response()->json(['albums_data' => [["0", __('keywords.NothingFound')]], 'pagination_info' => $parents->paginationInfo]);
        }
    }
}