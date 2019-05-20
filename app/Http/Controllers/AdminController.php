<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;

//We don't need the line below. May be we will need it in a future.
//use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(){
        $this->current_page = 'Home';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    //
    public function index(){
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('keywords.'.$this->current_page);
        
        return view('adminpages.adminhome')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle
            ]);
    }
    
    /*public function editHome() {
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        return view('adminpages.adminhome')->with([
            'main_links' => $main_links,
            'headTitle' => $headTitle
            ]);
        
    }*/
}
