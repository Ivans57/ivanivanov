<?php

namespace App\Http\Controllers;

//as no validation required, we can use just default request.
//use Illuminate\Http\Request;
use App\Http\Requests\AddEditUserRequest;
use App\User;
use App\MainLink;
use App\MainLinkUsers;
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

        //I will extract full_access_users and limited_access_users as shown below, because there is a confusion with field names when 
        //doing with model relations.
        $main_link_id = MainLink::select('id')->where('keyword', $section)->firstOrFail()->id;
        $full_and_limited_access_user_ids = MainLinkUsers::where('links_id', $main_link_id)
                                            ->select('full_access_users', 'limited_access_users')->firstOrFail();
        $users = array();
        
        $full_access_user_ids = json_decode($full_and_limited_access_user_ids->full_access_users, true);
        $limited_access_user_ids = json_decode($full_and_limited_access_user_ids->limited_access_users, true);
        
        if ($full_access_user_ids === null && $limited_access_user_ids != null) {
            $user_ids = $limited_access_user_ids;
        } else if ($limited_access_user_ids === null && $full_access_user_ids != null) {
            $user_ids = $full_access_user_ids;
        } elseif ($full_access_user_ids != null && $limited_access_user_ids != null) {
            $user_ids = array_merge($full_access_user_ids, $limited_access_user_ids);
        } else {
            $user_ids = [0];
        }
        
        foreach ($users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, $user_ids) == false) {
                $users[$user_from_database->id] = $user_from_database->name;
            }
        }
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'add',
            'users' => $users,
            'section' => $section,
            //The line below is required to keep fields activated or deactivated in different cases. 
            'users_array_size' => sizeof($users)
            ]);
    }
    
    public function join_for_albums(AddEditUserRequest $request) {
        
        $main_link_id = MainLink::select('id')->where('keyword', $request->section)->firstOrFail()->id;
        if ($request->full_access) {
            $user_ids = MainLinkUsers::where('links_id', $main_link_id)->select('full_access_users')->firstOrFail()->full_access_users;
        } else {
            $user_ids = MainLinkUsers::where('links_id', $main_link_id)->select('limited_access_users')->firstOrFail()->limited_access_users;
        }
        if ($user_ids === null) {
            $users_array[0] = $request->users;
            $users_json = json_encode($users_array);
        } else {
            //The second parameter should be set to true to get an array.
            $users_array = json_decode($user_ids, true);
            array_push($users_array, $request->users);
            $users_json = json_encode($users_array);
        }
        $edited_section = MainLinkUsers::where('links_id', $main_link_id)->firstOrFail();     
        if ($request->full_access) {
            $edited_section->full_access_users = $users_json;
        } else {
            $edited_section->limited_access_users = $users_json;
        }
        $edited_section->save();   
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
