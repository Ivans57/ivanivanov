<?php

namespace App\Http\Controllers;

//We need the line below to use localization. 
use App;
use App\Http\Repositories\AdminPicturesRepository;
use App\Http\Requests\CreatePictureRequest;
use App\Http\Requests\EditPictureRequest;
use App\Album;
use App\Picture;

class AdminPicturesController extends Controller
{
    protected $pictures;
    protected $current_page;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(AdminPicturesRepository $pictures) {
        $this->pictures = $pictures;        
        $this->current_page = 'Albums';
    }
    
    public function create($parent_keyword) {
        if ($parent_keyword != "0") {
            $parent_info = Album::select('id', 'album_name')
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
            'section' => 'albums',
            //The last variable is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'file'
            ]);
    }
    
    public function store(CreatePictureRequest $request) {
        
        $this->pictures->store($request);
        
        //The line below is left only for example
        //return back()->with('success', 'Image Uploaded Successfully')->with('path', $new_name);
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'store'
            ]);
    }
    
    public function edit($keyword, $parent_keyword, $search_is_on) {
        $edited_picture = Picture::where('keyword', '=', $keyword)->firstOrFail();
        
        if ($parent_keyword != "0") {
            $parent_info = Album::select('id', 'album_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }       
        return view('adminpages.pictures.create_and_edit_picture')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->album_name : null,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'edit',
            'edited_picture' => $edited_picture,
            'path_to_file' => (App::isLocale('en') ? 'storage/albums/en' : 'storage/albums/ru').
                                $this->pictures->getDirectoryPath($edited_picture->included_in_album_with_id)."/",
            //The line below is required for form path.
            'section' => 'albums',
            //The last variable is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'file',
            //The two vari/The twables below are required for edit in search mode.
            'parent_keyword' => $parent_keyword,
            'search_is_on' => $search_is_on
            ]);       
    }
    
    public function update($keyword, $parent_keyword, $search_is_on, EditPictureRequest $request) {      
        $this->pictures->update($keyword, $request);

        if ($search_is_on === "1") {
            $new_parent = Album::where('id', $request->included_in_album_with_id)->first();
            $new_parent_keyword = ($new_parent === null) ? "0" : $new_parent->keyword;
            $parent_keyword = ($parent_keyword === $new_parent_keyword) ? $parent_keyword : $new_parent_keyword;
        }
        
        //We need to show an empty form first to close
        //a pop up window. We are opening special close
        //form and thsi form is launching special
        //javascript which closing the pop up window
        //and reloading a parent page.
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'update',
            //The three variables below are required for edit in search mode.
            'parent_keyword' => $parent_keyword,
            'section' => 'albums',
            'search_is_on' => $search_is_on
            ]);
    }
}
