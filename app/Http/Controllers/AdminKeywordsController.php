<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\KeywordsRepository;
use App\Keyword;
use App\Http\Requests\CreateEditKeywordRequest;
//The reuqtes below is required for search.
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lower case.
use Illuminate\Support\Str;


class AdminKeywordsController extends Controller
{
    
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(KeywordsRepository $keyword){
        $this->keywords = $keyword;
        $this->current_page = 'Keywords';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();
        $this->is_admin_panel = true;
    }  

    //Optional argument is required for sorting.
    //The second parameter is required when user is searching something and there is more than one page of something found,
    //that will enable to turn pages of items found normaly, without dropping search.
    public function index($sorting_mode = null) {      
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);       
        $items_amount_per_page = 14;
        
        //In the next line the data are getting extracted from the database and sorted.
        $sorting_data = $this->keywords->sort(0, $items_amount_per_page, $sorting_mode);
                      
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($sorting_data["keywords"]->currentPage() > $sorting_data["keywords"]->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), 
                    $sorting_data["keywords"]->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.keywords.adminkeywords')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                //The variable below (section) is required to choose proper js files.
                'section' => Str::lower($this->current_page),
                'keywords' => $sorting_data["keywords"],
                'items_amount_per_page' => $items_amount_per_page,
                'sorting_asc_or_desc' => $sorting_data["sorting_asc_or_desc"],
                //The variable below is required to show field sorting tools correctly.
                //We cannot just stick to $keywords->total(), because on the second page, if there is only one keyword,
                //no sorting arrows will be shown.
                'all_keywords_amount' => Keyword::count(),
                //The variable below is required for sort to indicate which function to call index or search.
                'search_is_on' => 0
            ]);
        }
    }
    
    public function searchKeyword(Request $request) {
        $items_amount_per_page = 14;
        $keywords_with_info = $this->keywords->getKeywordsFromSearch($request->input('find_keywords_by_text'), $request->input('page_number'), 
                                                                     $items_amount_per_page, $request->input('sorting_mode'));
               
        $keywords = $keywords_with_info->keywords_on_page;
        $sorting_asc_or_desc = $keywords_with_info->sorting_asc_or_desc;
        $all_keywords_amount = $keywords_with_info->all_keywords_count;
        $pagination_info = $keywords_with_info->paginator_info;
        //The variable below is required for sort to indicate which function to call index or search.
        $search_is_on = 1;
        
        $title = view('adminpages.keywords.adminkeywords_search_keywords_title')->render();
        $control_buttons = view('adminpages.keywords.adminkeywords_searchcontrolbuttons')->render();
        
        $content = view('adminpages.keywords.adminkeywords_searchcontent', 
                compact("keywords", "sorting_asc_or_desc", "all_keywords_amount", "items_amount_per_page", 
                        "pagination_info", "search_is_on"))->render();
        
        return response()->json(compact("title", "control_buttons", "content"));
    }
    
    public function create() {   
        return view('adminpages.keywords.create_and_edit_keyword')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'create_or_edit' => 'create'
            ]);
    }
    
    //As we use JavaScipt to authorise filled form, we do not need any Request objects.
    //I left it for example. I will use this approcah for articles creation.
    public function store(CreateEditKeywordRequest $request) {      
        $this->keywords->store($request);
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'store'
            ]);
    }
    
     public function edit($keyword) {       
        //Belowe we are fetching the keyword we need to edit
        $keyword_to_edit = Keyword::where('keyword', '=', $keyword)->firstOrFail();
        
        return view('adminpages.keywords.create_and_edit_keyword')->with([
            //Actually we do not need any head title as it is just a partisal view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            'keyword' => $keyword_to_edit->keyword,
            'text' => $keyword_to_edit->text,
            'section' => $keyword_to_edit->section,
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use create or edit.
            'create_or_edit' => 'edit'
            ]);
        
    }
    
    public function update($keyword, CreateEditKeywordRequest $request) {       
        $this->keywords->update($keyword, $request);
        
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
    
    public function remove($keywords) {
        $keywords_array = $this->keywords->get_keywords_from_string($keywords);
        return view('adminpages.keywords.delete_keyword')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            'keywords' => $keywords,
            'plural_or_singular' => (sizeof($keywords_array) > 1) ? 'plural' : 'singular',
            ]);
               
    }
    
    public function destroy($keywords) {
        $this->keywords->destroy($keywords);       
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //Four variables below are required to make proper actions when pop up window closes.
            'action' => 'destroy',
            'section' => 'keywords',
            'parent_keyword' => '0',
            'parent_directory_is_empty' => (\App\Keyword::count()) > 0 ? 0 : 1
            ]);              
    }
}
