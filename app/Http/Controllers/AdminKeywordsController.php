<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
//use Illuminate\Http\Request;
use Request;

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
        
        $keywords = \App\Keyword::paginate($items_amount_per_page);
        
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
        
        return view('adminpages.keywords.create')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle
            ]);
    }
    
    public function store() {
        $input = Request::all();
        
        \App\Keyword::create($input);       
        
        if (App::isLocale('en')) {
            return redirect('admin/keywords');
        } 
        else {
            return redirect('ru/admin/keywords');
        }
    }
}
