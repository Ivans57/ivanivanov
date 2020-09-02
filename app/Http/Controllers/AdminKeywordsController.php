<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\KeywordsRepository;
use App\Keyword;
use App\Http\Requests\CreateEditKeywordRequest;
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

    //
    public function index() {      
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);       
        $items_amount_per_page = 14;       
        $keywords = Keyword::latest()->paginate($items_amount_per_page);

        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($keywords->currentPage() > $keywords->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $keywords->lastPage(), 
                                                                                $this->is_admin_panel);
        } else {
            return view('adminpages.adminkeywords')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                'keywords' => $keywords,
                'items_amount_per_page' => $items_amount_per_page
            ]);
        }
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
            'headTitle' => __('keywords.'.$this->current_page)
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
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
    public function remove($keyword) {            
        return view('adminpages.keywords.delete_keyword')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            'keyword' => $keyword,
            ]);
               
    }
    
    public function destroy($keyword) {      
        Keyword::where('keyword', '=', $keyword)->delete();
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
               
    }
}
