<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;
use App\Http\Repositories\FolderCreateOrEditRepository;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
//use Request;
use App\Http\Requests\CreateEditFolderRequest;
use App\Folder;


class AdminArticlesController extends Controller
{
    //
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(ArticlesRepository $articles) {
        
        $this->folders = $articles;
        $this->current_page = 'Articles';
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
        $folders = $this->folders->getAllFolders($items_amount_per_page);
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($folders->currentPage() > $folders->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $folders->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.adminfolders')->with([
                'main_links' => $main_links->mainLinks,
                'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
                'headTitle' => $headTitle,
                'folders' => $folders,
                'items_amount_per_page' => $items_amount_per_page
            ]);
        }
    }
    
    public function showFolder($keyword, $page) {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
              
        //We need to call the method below to clutter down current method in controller
        return $this->folders->showFolderView(Str::lower($this->current_page), $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel);
    }
    
    /*public function create() {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        return view('adminpages.adminfolders')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkStatus' => $main_links->keywordLinkIsActive,
            'headTitle' => $headTitle
            ]);
    }*/
    
    public function create($parent_keyword) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need this variable to find out which mode are we using Create or Edit
        //and then to open a view accordingly with a chosen mode.
        $create_or_edit = 'create';
        
        if ($parent_keyword != "0") {
            $parent_info = \App\Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
                      
        return view('adminpages.create_and_edit_directory')->with([
            'headTitle' => $headTitle,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            'create_or_edit' => $create_or_edit,
            //The line below is required for form path.
            'section' => 'articles',
            ]);
    }
    
    
    public function store(CreateEditAlbumRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
        
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent folder
        //then parent folder id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record.
        if ($input['included_in_folder_with_id'] == 0){
            $input['included_in_folder_with_id'] = NULL;
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
        Folder::create($input);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and this form is launching special
        //javascript which is closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function edit($keyword, $parent_keyword) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need this variable to find out which mode are we using Create or Edit
        //and then to open a view accordingly with a chosen mode.
        $create_or_edit = 'edit';
        
        $edited_folder = Folder::where('keyword', '=', $keyword)->firstOrFail();
        
        if ($parent_keyword != "0") {
            $parent_info = \App\Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
        
        return view('adminpages.create_and_edit_directory')->with([
            'headTitle' => $headTitle,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            'create_or_edit' => $create_or_edit,
            'edited_folder' => $edited_folder,
            //The line below is required for form path.
            'section' => 'articles',
            ]);
        
    }
    
    public function update($keyword, CreateEditFolderRequest $request) {
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
        
        $edited_folder = Folder::where('keyword', '=', $keyword)->firstOrFail();
        
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent folder
        //then parent folder id will be assigned 0 instead of NULL
        //which will cause an error when saving a new record.
        if ($input['included_in_folder_with_id'] == 0){
            $input['included_in_folder_with_id'] = NULL;
        }      
 
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }
        
        $input['updated_at'] = Carbon::now();
        
        $edited_folder->update($input);
        
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
        
        return view('adminpages.delete_folder')->with([//Need to make this view.
            'headTitle' => $headTitle,
            'keyword' => $keyword,
            //The line below is required for form path.
            'section' => 'articles',
            ]);
    }
    
    public function destroy($keyword) {
        
        //Actually we do not need any head title as it is just a partial view. 
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
        
        Folder::where('keyword', '=', $keyword)->delete();
        
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function findParents(Request $request) {
        
        $create_or_edit_window = new FolderCreateOrEditRepository();
        
        $parents = $create_or_edit_window->getParents($request->input('localization'), $request->input('page'), 
                $request->input('parent_search'), $request->input('keyword'));
                   
        if (count($parents->parentsDataArray) > 0) {              
            return response()->json(['directories_data' => $parents->parentsDataArray, 'pagination_info' => $parents->paginationInfo]);
        } else {
            //Here we need to override the following property, because in case user doesn't enter anything in search
            //system supposed to return nothing instead of everything and there shouldn't be any pages.
            $parents->paginationInfo->nextPage = null;
            return response()->json(['directories_data' => [["0", __('keywords.NothingFound')]], 'pagination_info' => $parents->paginationInfo]);
        }
    }
    
    public function getParentList(Request $request) {
        
        $create_or_edit_window = new FolderCreateOrEditRepository();
        
        //parent_id is an id of parent of the item being edited or when user wants to create a new folder in already existing folder.
        //parent_node_id is an id of folder which is getting opened id parent dropdown list to get its nested folders.
        $parents = $create_or_edit_window->getParentList($request->input('localization'), $request->input('page'), $request->input('parent_id'), 
                                $request->input('parent_node_id'), $request->input('keyword_of_directory_to_exclude'));
             
        return response()->json(['parent_list_data' => $parents->parentsDataArray, 'pagination_info' => $parents->paginationInfo]);
    }
}
