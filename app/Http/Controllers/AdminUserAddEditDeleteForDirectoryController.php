<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Repositories\AdminUsersAddEditDeleteForDirectoryRepository;

class AdminUserAddEditDeleteForDirectoryController extends Controller
{
    public function __construct(AdminUsersAddEditDeleteForDirectoryRepository $users) {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
        $this->users = $users;
        //There is no need for the property below, but need to assign it to avoid error.
        $this->current_page = '';
    }
    
    public function add_for_directory($directory_keyword) {
        
        $users = $this->users->get_users_for_add_for_directory($directory_keyword);
        
        return view('adminpages.add_edit_delete_users.add_edit_delete_user')->with([
            //Actually we do not need any head title as it is just a partial view
            //We need it only to make the variable initialized. Othervise there will be error.
            'headTitle' => __('keywords.'.$this->current_page),
            //We are going to use one view for create and edit
            //thats why we will nedd kind of indicator to know which option do we use
            //create or edit.
            'add_edit_or_delete' => 'add',
            'users' => $users,
            'section' => $directory_keyword,
            //The line below is required to keep fields activated or deactivated in different cases. 
            'users_array_size' => sizeof($users)
            ]);
    }
}
