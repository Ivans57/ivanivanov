<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\UsersRepository;

//We need the line below to peform some manipulations with strings
//e.g. making all string letters lower case.
use Illuminate\Support\Str;

class AdminUsersController extends Controller
{
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(UsersRepository $users) {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        $this->users = $users;
        $this->current_page = 'Users';
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
    public function index(/*$sorting_mode = null*/) {      
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);       
        //$items_amount_per_page = 14;
        
        //In the next line the data are getting extracted from the database and sorted.
        //$sorting_data = $this->keywords->sort(0, $items_amount_per_page, $sorting_mode);
                      
        return view('adminpages.users.adminusers')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                //The variable below (section) is required to choose proper js files.
                'section' => Str::lower($this->current_page)
            ]);
    }
}
