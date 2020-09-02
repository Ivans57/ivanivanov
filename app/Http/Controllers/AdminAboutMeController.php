<?php

namespace App\Http\Controllers;

//We need the line below to use localization. 
use App;
use App\Article;
use App\Http\Repositories\CommonRepository;


class AdminAboutMeController extends Controller
{
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(){
        $this->current_page = 'AboutMe';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    //
    public function index(){
        
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);

        $about_me =  Article::where('keyword', '=', $this->current_page)->first();
        
        //There is a check below if AboutMe article exists.
        //If it doesn't exist, there will be an empty page with a small manual what to do.
        //If it exists, then a user will be redirected to its edit page.
        if (is_null($about_me)) {
            return view('adminpages.adminAboutMe')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page)
                ]);
        } else {
            return App::isLocale('en') ? redirect('/admin/article/'.$this->current_page.'/edit/'.$this->current_page) : 
                                     redirect('/ru/admin/article/'.$this->current_page.'/edit/'.$this->current_page);
        }
        
        
    }
}
