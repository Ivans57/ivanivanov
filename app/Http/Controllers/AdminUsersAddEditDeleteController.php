<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\User;
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
    
    public function create_for_albums() {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'desc')->get();
        
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
            'create_edit_or_delete' => 'create',
            'users' => $users
            ]);
    }
}
