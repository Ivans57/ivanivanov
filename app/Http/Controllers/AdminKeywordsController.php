<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Keyword;
use Carbon\Carbon;
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
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
        //Actually we do not need any head title as it is just a partisal view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        //We need a list with all keywords to check whether the new keyword is unique
        $keywords_full_data = Keyword::select('keyword')->get();
        
        //There are lots of another data in the variable $keywords_full_data
        //Below I am extracting only required data and pushing it in the new array $keywords
        $keywords = array();
        
        foreach($keywords_full_data as $keyword_full_data) {
            array_push($keywords, $keyword_full_data->keyword);
        }
        
        //As there is not possible to pass any arrays to the view or javascript,
        //we need to convert the $keywords array to json
        $keywords_json = json_encode($keywords);
        
        //We are going to use one view for create and edit
        //thats why we will nedd kind of indicator to know which option do we use
        //create or edit.
        $create_or_edit = 'create';
        
        return view('adminpages.keywords.create_and_edit')->with([
            'headTitle' => $headTitle,
            'keywords' => $keywords_json,
            'create_or_edit' => $create_or_edit
            ]);
    }
    
    //As we use JavaScipt to authorise filled form, we do not need any Request objects.
    //I left it for example. I will use this approcah for articles creation.
    public function store() {
        
        //The line below left just for example
        //$input = Request::all();
        
        $input = new Keyword();
        
        $input['keyword'] = filter_input(INPUT_POST, 'keyword');
        
        $input['text'] = filter_input(INPUT_POST, 'text');
        
        $input['created_at'] = Carbon::now();
        
        $input['updated_at'] = Carbon::now();
        
        $input->save();
        
    }
    
     public function edit($keyword_id) {
        
        //Actually we do not need any head title as it is just a partisal view
        //We need it only to make the variable initialized. Othervise there will be error.
         $headTitle= __('keywords.'.$this->current_page);
        
        //We need a list with all keywords to check whether the new keyword is unique
        $keywords_full_data = Keyword::select('keyword')->get();
        
        //There are lots of another data in the variable $keywords_full_data
        //Below I am extracting only required data and pushing it in the new array $keywords
        $keywords = array();
        
        foreach($keywords_full_data as $keyword_full_data) {
            array_push($keywords, $keyword_full_data->keyword);
        }
        
        //As there is not possible to pass any arrays to the view or javascript,
        //we need to convert the $keywords array to json
        $keywords_json = json_encode($keywords);
        
        //Belowe we are fetching the keyword we need to edit
        $keyword_to_edit = Keyword::findOrFail($keyword_id);
        
        //We are going to use one view for create and edit
        //thats why we will nedd kind of indicator to know which option do we use
        //create or edit.
        $create_or_edit = 'edit';
        
        return view('adminpages.keywords.create_and_edit')->with([
            'headTitle' => $headTitle,
            'keywords' => $keywords_json,
            'keyword_to_edit_id' => $keyword_to_edit->id,
            'keyword_to_edit_keyword' => $keyword_to_edit->keyword,
            'keyword_to_edit_text' => $keyword_to_edit->text,
            'create_or_edit' => $create_or_edit
            ]);
        
    }
    
    public function update(Request $request, $keyword_id) {
        
        $edit = Keyword::findOrFail($keyword_id);       
        
        $edit['keyword'] = $request->input('keyword');
        
        $edit['text'] = $request->input('text');
        
        $edit['updated_at'] = Carbon::now();
        
        $edit->update();
               
    }
    
    public function remove($keyword_id) {
        
        $headTitle= __('keywords.'.$this->current_page);
        
        return view('adminpages.keywords.delete')->with([
            'headTitle' => $headTitle,
            'keyword_id' => $keyword_id
            ]);
               
    }
    
    public function destroy($keyword_id) {
               
        $destroy = Keyword::findOrFail($keyword_id);
        
        $destroy->delete();
               
    }
}
