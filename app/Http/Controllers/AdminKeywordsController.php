<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Keyword;
use Carbon\Carbon;
//We don't nedd the lines commented below, but I left them just in case
//use Illuminate\Http\Request;
//use Request;
//use App\Http\Requests;
//use App\Http\Requests\CreateKeywordRequest;
//use Illuminate\Http\Response;
//use App;


class AdminKeywordsController extends Controller
{
    
    protected $current_page;
    protected $navigation_bar_obj;
    
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
    }  

    //
    public function index() {
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        $items_amount_per_page = 14;
        
        $keywords = Keyword::latest()->paginate($items_amount_per_page);

        return view('adminpages.keywords.adminkeywords')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'keywords' => $keywords,
            'items_amount_per_page' => $items_amount_per_page
            ]);
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
    
    public function update($keyword_id) {
        
        $edit = Keyword::findOrFail($keyword_id);
        
        $edit['keyword'] = filter_input(INPUT_POST, 'keyword');
        
        $edit['text'] = filter_input(INPUT_POST, 'text');
        
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
