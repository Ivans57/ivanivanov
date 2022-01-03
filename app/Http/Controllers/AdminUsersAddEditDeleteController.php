<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AdminUsersAddEditDeleteRepository;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditOrDeleteUserInSectionRequest;

class AdminUsersAddEditDeleteController extends Controller
{
    public function __construct(AdminUsersAddEditDeleteRepository $users) {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        $this->users = $users;
        //There is no need for the property below, but need to assign it to avoid error.
        $this->current_page = '';
    }
    
    public function add_for_section($section) {
        
        $users = $this->users->get_users_for_add_for_section($section);
        
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
    
    public function join_for_section(AddUserRequest $request) {
        
        $this->users->join_user_for_section($request);
        
        return view('adminpages.user_add_edit_delete_form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
    public function edit_for_section($section) {
        
        $users_and_accesses = $this->users->get_users_for_edit_for_section($section);
        
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
            'access_status_of_first_element' => $this->users->get_status_of_first_user_for_section($users_and_accesses)
            ]);
    }
    
    public function update_for_section(EditOrDeleteUserInSectionRequest $request) {
        
        $this->users->update_user_for_section($request);
        
        return view('adminpages.user_add_edit_delete_form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
    
    public function delete_for_section($section) {
        
        $users = $this->users->get_users_for_delete_for_section($section);
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'delete',
            'users' => $users,
            'section' => $section,
            //The line below is required to keep fields activated or deactivated in different cases. 
            'users_array_size' => sizeof($users)
            ]);
    }
    
    public function destroy_for_section(EditOrDeleteUserInSectionRequest $request) {
        
        $this->users->destroy_user_for_section($request);
        
        return view('adminpages.user_add_edit_delete_form_close')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page)
            ]);
    }
}
