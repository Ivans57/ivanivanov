<?php

namespace App\Http\Controllers;

//We need the line below to use localization. 
use App;
use Carbon\Carbon;
use App\Picture;
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
    
    public function create($parent_keyword) {
        if ($parent_keyword != "0") {
            $parent_info = \App\Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }
        
        return view('adminpages.pictures.create_and_edit_picture')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'create',
            //The line below is required for parent search and select and is being used in javascript.
            'section' => 'albums'
            ]);
    }
    
    public function store(Request $request) {
        $this->validate($request, [
            'image_select'  => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
            ]);

        if (App::isLocale('en')) {
            $root_path = storage_path('app/public/albums/en');
        } else {
            $root_path = storage_path('app/public/albums/ru');
        }
        
        $image = $request->file('image_select');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        
        $input = $request->all();      
        /*if ($input['included_in_album_with_id'] == 0) {
            $input['included_in_album_with_id'] = NULL;
        }*/
        $input['included_in_album_with_id'] = 464;
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }       
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $input['file_name'] = $new_name;
        Picture::create($input);
        
            
        $image->move($root_path, $new_name);

        //The line below is left only for example
        //return back()->with('success', 'Image Uploaded Successfully')->with('path', $new_name);
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
}
