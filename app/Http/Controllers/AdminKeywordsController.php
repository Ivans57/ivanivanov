<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Keyword;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
    public function __construct(){
        
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
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        $items_amount_per_page = 14;
        
        $keywords = Keyword::latest()->paginate($items_amount_per_page);

        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($keywords->currentPage() > $keywords->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $keywords->lastPage(), $this->is_admin_panel);
        } else {
            return view('adminpages.keywords.adminkeywords')->with([
                'main_links' => $main_links->mainLinks,
                'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
                'headTitle' => $headTitle,
                'keywords' => $keywords,
                'items_amount_per_page' => $items_amount_per_page
            ]);
        }
    }
    
    public function create() {
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        //We are going to use one view for create and edit
        //thats why we will nedd kind of indicator to know which option do we use
        //create or edit.
        $create_or_edit = 'create';
        
        return view('adminpages.keywords.create_and_edit_keyword')->with([
            'headTitle' => $headTitle,
            'create_or_edit' => $create_or_edit
            ]);
    }
    
    //As we use JavaScipt to authorise filled form, we do not need any Request objects.
    //I left it for example. I will use this approcah for articles creation.
    public function store(CreateEditKeywordRequest $request) {//+
        
        $headTitle= __('keywords.'.$this->current_page);
        
        $input = $request->all();
               
        $input['created_at'] = Carbon::now();        
        $input['updated_at'] = Carbon::now();
        
        Keyword::create($input);
        
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
     public function edit($keyword_id) {
        
        //Actually we do not need any head title as it is just a partisal view
        //We need it only to make the variable initialized. Othervise there will be error.
         $headTitle= __('keywords.'.$this->current_page);
        
        //Belowe we are fetching the keyword we need to edit
        $keyword_to_edit = Keyword::findOrFail($keyword_id);
        
        //We are going to use one view for create and edit
        //thats why we will nedd kind of indicator to know which option do we use
        //create or edit.
        $create_or_edit = 'edit';
        
        return view('adminpages.keywords.create_and_edit_keyword')->with([
            'headTitle' => $headTitle,
            'keyword_to_edit_id' => $keyword_to_edit->id,//Need to check if we need this field!
            'keyword_to_edit_keyword' => $keyword_to_edit->keyword,
            'keyword_to_edit_text' => $keyword_to_edit->text,
            'create_or_edit' => $create_or_edit
            ]);
        
    }
    
    public function update($keyword, CreateEditAlbumRequest $request) {//+
        
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
        
        $edited_keyword = Keyword::where('keyword', '=', $keyword)->firstOrFail();
        
        $input = $request->all();      
        $input['updated_at'] = Carbon::now();
        
        $edited_keyword->update($input);
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
    }
    
    public function remove($keyword) {
        
        $headTitle= __('keywords.'.$this->current_page);
        
        return view('adminpages.keywords.delete_keyword')->with([
            'headTitle' => $headTitle,
            'keyword' => $keyword,
            ]);
               
    }
    
    public function destroy($keyword) {
               
        //Actually we do not need any head title as it is just a partial view.
        //We need it only to make the variable initialized. Othervise there will be an error.
        $headTitle= __('keywords.'.$this->current_page);
        
        //return 'Delete '.$keyword.'?';
        Keyword::where('keyword', '=', $keyword)->delete();
        
        return view('adminpages.form_close')->with([
            'headTitle' => $headTitle
            ]);
               
    }
}
