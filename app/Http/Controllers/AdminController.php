<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct() {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        $this->current_page = 'Start';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    public function index() {
        
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);
        
        return view('adminpages.adminstart')->with([
            //The variable below is required to show current user which is logged in.
            'current_user_name' => Auth::user()->name,
            //Below main website links.
            'main_ws_links' => $main_links->mainWSLinks,
            //Below main admin panel links.
            'main_ap_links' => $main_links->mainAPLinks,
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
}
