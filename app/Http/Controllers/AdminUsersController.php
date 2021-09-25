<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AdminUsersRepository;
use App\User;
use App\Http\Requests\CreateEditUserRequest;

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
    public function __construct(AdminUsersRepository $users) {
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
    public function index($sorting_mode = null) {      
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);       
        
        //In the next line the data are getting extracted from the database and sorted.
        $sorting_data = $this->users->sort($sorting_mode);
              
        return view('adminpages.users.adminusers')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => __('keywords.'.$this->current_page),
                //The variable below (section) is required to choose proper js files.
                'section' => Str::lower($this->current_page),            
                'users' => $sorting_data["users"],
                'sorting_asc_or_desc' => $sorting_data["sorting_asc_or_desc"],
                'all_users_amount' => User::count()
            ]);
    }
    
    public function create() {
        return view('adminpages.users.create_and_edit_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'create_or_edit' => 'create'
            ]);
    }
    
    public function store(CreateEditUserRequest $request) {      
        $this->users->store($request);
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'store'
            ]);
    }
}
