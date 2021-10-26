<?php

namespace App\Http\Controllers;

//as no validation required, we can use just default request.
use Illuminate\Http\Request;
use App\User;
use App\MainLink;
//The line below is required to make query conditions using merged table's fields.
use Illuminate\Database\Eloquent\Builder;

class AdminUsersAddEditDeleteController extends Controller
{
    public function __construct() {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        //There is no need for the property below, but need to assign it to avoid error.
        $this->current_page = '';
    }
    
    public function add_for_albums($section) {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'asc')->get();

        $full_access_user_ids = MainLink::where('keyword', $section)->select('full_access_users')->firstOrFail()->full_access_users;
        $limited_access_user_ids = MainLink::where('keyword', $section)->select('limited_access_users')->firstOrFail()->limited_access_users;
        
        $users = array();

        foreach ($users_from_database as $user_from_database) {
            $users[$user_from_database->id] = $user_from_database->name;
        }
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'add',
            'users' => $users
            ]);
    }
    
    public function join_for_albums(Request $request) {
        
        $users = $request->users;
        
        $full_access = $request->full_access;
        //'albums' should be changed to variable $section.
        $full_access_user_ids = MainLink::where('keyword', 'albums')->select('full_access_users')->firstOrFail()->full_access_users;
        
        if ($full_access_user_ids === null) {
            //$users = [1, 2];
            $users_json = json_encode($users);
            //$users_json = $users->toJson();
            //$books->toJson();
            //"{"0":1,"1":2,"2":3,"3":4,"4":5,"5":6,"10":56}"
            //'albums' should be changed to variable $section.
            $edited_section = MainLink::where('keyword', 'albums')->firstOrFail();     
            //$edited_section->full_access_users = ["32", "31"];
            $edited_section->full_access_users = $users_json;
            $edited_section->save();    
        }
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'add'
            ]);
    }
    
    public function edit_for_albums() {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'asc')->get();
        
        $users = array();
        
        foreach ($users_from_database as $user_from_database) {
            $users[$user_from_database->id] = $user_from_database->name;
        }
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'edit',
            'users' => $users
            ]);
    }
    
    public function delete_for_albums() {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'asc')->get();
        
        $users = array();
        
        foreach ($users_from_database as $user_from_database) {
            $users[$user_from_database->id] = $user_from_database->name;
        }
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'delete',
            'users' => $users
            ]);
    }
}
