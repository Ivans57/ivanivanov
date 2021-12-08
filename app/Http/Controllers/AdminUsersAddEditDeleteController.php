<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AdminUsersAddEditDeleteRepository;
use App\Http\Requests\AddEditUserRequest;
use App\User;
//use App\MainLink;
//use App\MainLinkUsers;
//The line below is required to make query conditions using merged table's fields.
use Illuminate\Database\Eloquent\Builder;

class AdminUsersAddEditDeleteController extends Controller
{
    public function __construct(AdminUsersAddEditDeleteRepository $users) {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        $this->users = $users;
        //There is no need for the property below, but need to assign it to avoid error.
        $this->current_page = '';
    }
    
    public function add_for_albums($section) {
        
        $users = $this->users->get_users_for_add_for_albums($section);
        
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
        
        $this->users->join_user_for_albums($request);
        
        return view('adminpages.form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //The variable below is required to make proper actions when pop up window closes.
            'action' => 'add'
            ]);
    }
    
    public function edit_for_albums($section) {
        
        $users_and_accesses = $this->users->get_users_for_edit_for_albums($section);
        
        if (sizeof($users_and_accesses) != 0) {
            //The next two lines below are required to tick the checkbox properly according to the acees status of the first user in dropdown list.
            reset($users_and_accesses);       
            $access_status_of_first_element = $users_and_accesses[key($users_and_accesses)]->access;
        } else {
            //We need to assign the variable below to avoid an error.
            $access_status_of_first_element = 0;
        }
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'edit',
            'users' => $users_and_accesses,
            'section' => $section,
            //The line below is required to keep fields activated or deactivated in different cases. 
            'users_array_size' => sizeof($users_and_accesses),
            //The next line below are required to tick the checkbox properly according to the acees status of the first user 
            //in dropdown list.
            'access_status_of_first_element' => $access_status_of_first_element
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
