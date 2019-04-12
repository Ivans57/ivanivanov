<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Keyword;
//use Illuminate\Http\Request;
use Request;
//use App\Http\Requests;
//use App\Http\Requests\CreateKeywordRequest;
//use Illuminate\Http\Response;
use Carbon\Carbon;


//We need this line below to check our localization
use App;

class AdminKeywordsController extends Controller
{
    //
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(){
        
        //$this->folders = $articles;
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
        $headTitle= __('mainLinks.'.$this->current_page);
        
        $items_amount_per_page = 14;
        
        //$keywords = \App\Keyword::latest()->get();
        $keywords = \App\Keyword::latest()->paginate($items_amount_per_page);
        //$keywords = \App\Keyword::orderBy('created_at', 'desc')->first();
        //$keywords = \App\Keyword::get();

        return view('adminpages.keywords.adminkeywords')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'keywords' => $keywords,
            'items_amount_per_page' => $items_amount_per_page
            ]);
    }
    
    public function create() {
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        //We need a list with all keywords to check whether the new keyword is uniq
        $keywords_full_data = \App\Keyword::select('keyword')->get();
        
        //There are lots of another data in the variable $keywords_full_data
        //Below I am extracting only required data and pushing it in the new array $keywords
        $keywords = array();
        
        foreach($keywords_full_data as $keyword_full_data) {
            array_push($keywords, $keyword_full_data->keyword);
        }
        
        //As there is not possible to pass any arrays to the view or javascript,
        //we need to convert the $keywords array to json
        $keywords_json = json_encode($keywords);
        
        return view('adminpages.keywords.create')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'keywords' => $keywords_json
            ]);
    }
    
    //As we use JavaScipt to authorise filled form, we do not need any Request objects.
    //I left it for example. I will use this approcah for articles creation.
    public function store(/*CreateKeywordRequest $request*/Request $request) {
        
        //Keyword::create($request->all());       
        
        //$input = Request::all();
        //$input = $request->all();
        $input['keyword'] = $request->input('keyword');
        $input['text'] = $request->input('text');
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        Keyword::create($input);
        
        /*if (App::isLocale('en')) {
            return redirect('admin/keywords');
            //return redirect()->route('admin/keywords')->refresh();
            //return redirect()->action('HomeController@index');
        } 
        else {
            return redirect('ru/admin/keywords')->refresh();
            //return redirect()->route('ru/admin/keywords')->refresh();
        }*/
    }
}
