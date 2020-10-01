<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AdminArticlesRepository;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;
use App\Http\Requests\CreateEditFolderRequest;
use App\Folder;


class AdminArticlesController extends Controller
{
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(AdminArticlesRepository $articles) {       
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
    
    
    public function index($sorting_mode = null) {
        
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);        
        //We need the variable below to display how many items we need to show per one page.
        $items_amount_per_page = 14;
        
        //In the next line the data are getting extracted from the database and sorted.
        $sorting_data = $this->folders->sort($items_amount_per_page, $sorting_mode, 1, 'folders');
        
        //On the line below we are fetching all articles from the database.
        //$folders = $this->folders->getAllFolders($items_amount_per_page, 1);
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page.
        //To avoid indefinite looping need to check whether a section has at least one element.
        if ($sorting_data["folders_or_articles"][0] && 
                ($sorting_data["folders_or_articles"]->currentPage() > $sorting_data["folders_or_articles"]->lastPage())) {
                    return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), 
                    $sorting_data["folders"]->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.adminfolders')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                'folders' => $sorting_data["folders_or_articles"],
                'items_amount_per_page' => $items_amount_per_page,
                'sorting_asc_or_desc' => $sorting_data["sorting_asc_or_desc"],
                //If we open just a root path of Folders, we won't have any parent keyword,
                //to avoid an exception we will assign it 0.
                'parent_keyword' => "0",
            ]);
        }
    }
    
    public function showFolder($keyword, $page, $sorting_mode = null) {
        
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
              
        //We need to call the method below to clutter down current method in controller
        return $this->folders->showFolderView(Str::lower($this->current_page), 
                                            $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel, 1, $sorting_mode);
    }
    
    public function create($parent_keyword) {      
        if ($parent_keyword != "0") {
            $parent_info = \App\Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }                     
        return view('adminpages.directory.create_and_edit_directory')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'create',
            //The line below is required for form path.
            'section' => 'articles',
            //The last variable is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'directory'
            ]);
    }
       
    public function store(CreateEditFolderRequest $request) {           
        $this->folders->store($request);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and this form is launching special
        //javascript which is closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
    public function edit($keyword, $parent_keyword) {            
        if ($parent_keyword != "0") {
            $parent_info = \App\Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }       
        return view('adminpages.directory.create_and_edit_directory')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'edit',
            'edited_directory' => Folder::where('keyword', '=', $keyword)->firstOrFail(),
            //The line below is required for form path.
            'section' => 'articles',
            //The last variable is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'directory'
            ]);
        //return response()->json(['html'=>$view]);
    }
    
    public function update($keyword, CreateEditFolderRequest $request) {    
        $this->folders->update($keyword, $request);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
    public function delete($entity_types_and_keywords) {
        //Getting an array of arrays of directories (folders) and files (articles).
        //This is required for view when need to mention proper entity names 
        //(folders and articles, or both, single and plural), rules.
        //There is nothing to do with a navigation bar, it is just a name of variable for Common Repository.
        $direcotries_and_files = $this->navigation_bar_obj->get_directories_and_files_from_string($entity_types_and_keywords);
        
        //There might be three types of views for return depends what user needs to delete,
        //folder(s), article(s), both folders and articles.
        return $this->folders->return_delete_view($direcotries_and_files, $entity_types_and_keywords, $this->current_page);    
    }
    
    public function destroy($entity_types_and_keywords) {
        $this->folders->destroy($entity_types_and_keywords);
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view. 
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
}
