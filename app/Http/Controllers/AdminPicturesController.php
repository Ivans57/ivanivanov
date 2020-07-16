<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use Illuminate\Http\Request;

class AdminPicturesController extends Controller
{
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct() {        
        $this->current_page = 'Albums';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();
        $this->is_admin_panel = true;
    }
    
    public function create() {
        
        //Actually we do not need any head title as it is just a partial view
        //We need it only to make the variable initialized. Othervise there will be error. 
        $headTitle= __('keywords.'.$this->current_page);
        
        
        return view('adminpages.pictures.create_and_edit_picture')->with([
            'headTitle' => $headTitle         
            ]);
    }
    
    public function store(Request $request) {
        $this->validate($request, [
            'select_file'  => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

        $image = $request->file('select_file');

        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        
        $image->move(public_path('images/pages'), $new_name);
        //File::mkdir($path);
        return back()->with('success', 'Image Uploaded Successfully')->with('path', $new_name);
    }
    
}
