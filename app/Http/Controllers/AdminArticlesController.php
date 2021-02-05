<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AdminArticlesRepository;
use App\Folder;
use App\Http\Requests\CreateEditFolderRequest;
//The reuqtes below is required for search.
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;


class AdminArticlesController extends Controller
{
    protected $current_page;
    //This property is required for Common Repositry where are all common functions.
    protected $common;
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
        $this->common = new CommonRepository();
        $this->is_admin_panel = true;
    }
    
    
    public function index($show_invisible = "all", $sorting_mode = null) {
        
        $main_links = $this->common->get_main_links_for_admin_panel_and_website($this->current_page);        
        //We need the variable below to display how many items we need to show per one page.
        $items_amount_per_page = 14;
        
        //In the next line the data are getting extracted from the database and sorted.
        //The fifth parameter is 'folders', because currently we are working with level 0 folders.
        //The first parameter is required to show if search mode has been activated.
        $sorting_data = $this->common->sort_for_albums_or_articles(0, $items_amount_per_page, $sorting_mode, 
                                                                    $show_invisible === "all" ? 1 : 0, 'folders');
        
        //The variable below is required to show field sorting tools correctly.       
        $all_items_amount = ($show_invisible=='all') ? Folder::where('included_in_folder_with_id', '=', null)->count() : 
                                                       Folder::where('included_in_folder_with_id', '=', null)->where('is_visible', '=', 1)->count();
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page.
        //To avoid indefinite looping need to check whether a section has at least one element.
        if ($sorting_data["directories_or_files"][0] && 
                ($sorting_data["directories_or_files"]->currentPage() > $sorting_data["directories_or_files"]->lastPage())) {
                    return $this->common->redirect_to_last_page_one_entity(Str::lower($this->current_page), 
                    $sorting_data["directories_or_files"]->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.folders.adminfolders')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                'folders' => $sorting_data["directories_or_files"],
                'section' => Str::lower($this->current_page),
                'items_amount_per_page' => $items_amount_per_page,
                'sorting_asc_or_desc' => $sorting_data["sorting_asc_or_desc"],
                //The variable below is required to keep previous 
                //sorting options in case all elements are invisible and user wants to make them visible again.
                'sorting_method_and_mode' => ($sorting_mode) ? $sorting_mode : '0',
                'show_invisible' => $show_invisible == 'all' ? 'all' : 'only_visible',
                //If we open just a root path of Folders, we won't have any parent keyword,
                //to avoid an exception we will assign it 0.
                'parent_keyword' => "0",
                //The line below is required to show correctly display_invisible elements.
                'all_folders_count' => Folder::where('included_in_folder_with_id', '=', null)->count(),
                'all_items_amount' => $all_items_amount,
                //The variable below is required for sort to indicate which function to call index or search.
                'search_is_on' => "0",
                'what_to_search' => 'folders'
            ]);
        }
    }
    
    public function showFolder($keyword, $page, $show_invisible, $sorting_mode = null, $folders_or_articles_first = null) {
        
        $main_links = $this->common->get_main_links_for_admin_panel_and_website($this->current_page);
        
        //We need to call the method below to clutter down current method in controller
        return $this->folders->showFolderView(Str::lower($this->current_page), 
                    $page, $keyword, 14 /*items_amount_per_page*/, $main_links, $this->is_admin_panel, 
                    $show_invisible == "only_visible" ? 0 : 1, $sorting_mode, $folders_or_articles_first);
    }
    
    public function searchFolderOrArticle(Request $request) {
        $items_amount_per_page = 14;
        $show_only_visible = ($request->input('show_only_visible') === null) ? 'all' : $request->input('show_only_visible');
        
        //The fourth parameter about visibility cannot be passed as it is, because when user is switching from normal mode to serach mode previous visibility rule
        //should be discarded.
        $folders_or_articles_with_info = $this->folders->getFoldersOrArticlesFromSearch($request->input('find_by_name'), $request->input('page_number'), 
                                                                $items_amount_per_page, $request->input('what_to_search'), $request->input('search_is_on') == '0' ? 'all' : 
                                                                $show_only_visible, $request->input('sorting_mode'));
               
        $folders_or_articles = $folders_or_articles_with_info->items_on_page;
        $sorting_asc_or_desc = $folders_or_articles_with_info->sorting_asc_or_desc;
        $all_items_amount = $folders_or_articles_with_info->all_items_count;
        //The variable below is required to display bisibility checkbox properly.
        $all_items_amount_including_invisible = $folders_or_articles_with_info->all_items_count_including_invisible;
        $pagination_info = $folders_or_articles_with_info->paginator_info;
        //The variable below is required for sort to indicate which function to call index or search.
        $search_is_on = "1";
        $show_invisible = $request->input('search_is_on') == '0' ? 'all' : $show_only_visible;
        $sorting_method_and_mode = ($request->input('sorting_mode') === null) ? "0" : $request->input('sorting_mode');
        $section = "articles";
        $what_to_search = $request->input('what_to_search');
        
        $path = "";       
        $title = view('adminpages.folders.adminfolder_search_folder_title')->render();       
        $control_buttons = view('adminpages.folders.adminfolders_searchcontrolbuttons')->render();
        
        $content = view('adminpages.folders.adminfolders_searchcontent', 
                compact("folders_or_articles", "sorting_asc_or_desc", "all_items_amount", "items_amount_per_page", "pagination_info", "search_is_on", "show_invisible", 
                        "all_items_amount_including_invisible", "sorting_method_and_mode", "section", "what_to_search"))->render();
        
        
        return response()->json(compact('path', 'title', 'control_buttons', 'content'));
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
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'store'
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
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'update'
            ]);
    }
    
    public function delete($entity_types_and_keywords, $parent_folder) {
        //Getting an array of arrays of directories (folders) and files (articles).
        //This is required for view when need to mention proper entity names 
        //(folders and articles, or both, single and plural), rules.
        //There is nothing to do with a navigation bar, it is just a name of variable for Common Repository.
        $direcotries_and_files = $this->common->get_directories_and_files_from_string($entity_types_and_keywords);
        
        //There might be three types of views for return depends what user needs to delete,
        //folder(s), article(s), both folders and articles.
        return $this->folders->return_delete_view($direcotries_and_files, $entity_types_and_keywords, $this->current_page, $parent_folder);    
    }
       
    public function destroy($section, $entity_types_and_keywords, $parent_folder) {
        $this->folders->destroy($entity_types_and_keywords);
        
        //The lines below are required to show sorting tools correctly after delete of any item.
        $parent = \App\Folder::select('id')->where('keyword', '=', $parent_folder)->first();
        
        $parent_directory_is_empty = 1;
        
        if ((\App\Folder::where('included_in_folder_with_id', '=', (($parent) ? $parent->id : null))->count()) > 0 || 
            (\App\Article::where('folder_id', '=', (($parent) ? $parent->id : null))->count()) > 0) {
            $parent_directory_is_empty = 0;    
        }
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view. 
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            'parent_keyword' => $parent_folder,
            'section' => $section,
            'parent_directory_is_empty' => $parent_directory_is_empty,
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'destroy'
            ]);
    }
}
