<?php

namespace App\Http\Repositories;

use App\User;
use App\MainLink;
use App\MainLinkUsers;
//The line below is required to make query conditions using merged table's fields.
use Illuminate\Database\Eloquent\Builder;


//This is for updating user access.
class UserNameAndAccess {
    public $name;
    public $access;
}

//The class below is designed to keep all non-admin users from database and all users ids, which are added to some particular section.
class UsersFromDataBaseAndAddedUsersIDs {
    public $users_from_database;
    public $full_and_limited_access_user_ids;
}

class AdminUsersAddEditDeleteRepository {
    
    public function get_users_for_add_for_albums($section) {
        
        $users_from_database_and_added_users_ids = $this->get_users_from_database_and_added_users_ids($section);
        
        $users = array();               
        foreach ($users_from_database_and_added_users_ids->users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode(
                                             $users_from_database_and_added_users_ids->full_and_limited_access_user_ids->full_access_users, 
                                             true), 
                                             json_decode(
                                             $users_from_database_and_added_users_ids->full_and_limited_access_user_ids->limited_access_users, 
                                             true))) == false) {
                $users[$user_from_database->id] = $user_from_database->name;
            }
        }
        return $users;
    }
    
    public function get_users_for_edit_for_albums($section) {
        
        $users_from_database_and_added_users_ids = $this->get_users_from_database_and_added_users_ids($section);
        
        $user_names_and_accesses = array();       
        foreach ($users_from_database_and_added_users_ids->users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode($users_from_database_and_added_users_ids->full_and_limited_access_user_ids->full_access_users, true), 
                                             json_decode($users_from_database_and_added_users_ids->full_and_limited_access_user_ids->limited_access_users, true))) == true) {
                $user_name_and_access = new UserNameAndAccess();
                $user_name_and_access->name = $user_from_database->name;
                $user_name_and_access->access = $this->get_user_access($user_from_database->id, 
                                                                json_decode($users_from_database_and_added_users_ids->full_and_limited_access_user_ids->full_access_users, true), 
                                                                json_decode($users_from_database_and_added_users_ids->full_and_limited_access_user_ids->limited_access_users, true));
                $user_names_and_accesses[$user_from_database->id] = $user_name_and_access;
            }
        }     
        return $user_names_and_accesses;
    }
    
    //The function below is required to tick the checkbox properly according to the acees status 
    //of the first user in dropdown list.
    public function get_status_of_first_user_for_albums($users_and_accesses) {
        if (sizeof($users_and_accesses) != 0) {
            //The next two lines below are required to tick the checkbox properly according to the acees status 
            //of the first user in dropdown list.
            reset($users_and_accesses);       
            $access_status_of_first_element = $users_and_accesses[key($users_and_accesses)]->access;
        } else {
            //We need to assign the variable below to avoid an error.
            $access_status_of_first_element = 0;
        }
        return $access_status_of_first_element;
    }
    
    //The method below retrieves all non-admin users from database and gives all users ids, which are added to some particular section.
    private function get_users_from_database_and_added_users_ids ($section) {
        
        $users_from_database_and_added_users_ids = new UsersFromDataBaseAndAddedUsersIDs();
        
        $users_from_database_and_added_users_ids->users_from_database = User::select('id', 'name')
                                                                              ->with('role_and_status')
                                                                              ->whereHas('role_and_status', function (Builder $query) { 
                                                                                $query->where('role', '=', 'user'); 
                                                                              })->orderBy('name', 'asc')->get();

        //I will extract full_access_users and limited_access_users as shown below, because there is a confusion with field names when 
        //doing with model relations.
        $users_from_database_and_added_users_ids->full_and_limited_access_user_ids = MainLinkUsers::where('links_id', 
                                                                 MainLink::select('id')->where('keyword', $section)->firstOrFail()->id)
                                                                 ->select('full_access_users', 'limited_access_users')->firstOrFail();
        return $users_from_database_and_added_users_ids;
    }
    
    //The method below is required to get an information whether some particular user has limited or full access to some section
    //it has been added to.
    private function get_user_access($user_id, $full_access_user_ids , $limited_access_user_ids) {
        
        //The check below is required to avoid an error in case $full_access_user_ids or $limited_access_user_ids is null.
        if (!$full_access_user_ids) {
            $full_access_user_ids = [];
        } else if (!$limited_access_user_ids) {
            $limited_access_user_ids = [];
        }
        
        if (in_array($user_id, $full_access_user_ids) == true) {
            return 'full';
        } else if (in_array($user_id, $limited_access_user_ids) == true) {
            return 'limited';
        }
    }
    
    //The method below is required to get users ids to check whether being checked user id is added to selected section.
    private function get_user_ids($full_access_user_ids, $limited_access_user_ids) {
        
        if ($full_access_user_ids === null && $limited_access_user_ids != null) {
            $user_ids = $limited_access_user_ids;
        } else if ($limited_access_user_ids === null && $full_access_user_ids != null) {
            $user_ids = $full_access_user_ids;
        } elseif ($full_access_user_ids != null && $limited_access_user_ids != null) {
            $user_ids = array_merge($full_access_user_ids, $limited_access_user_ids);
        } else {
            $user_ids = [0];
        }
        return $user_ids;
    }
    
    public function join_user_for_albums($request) {
        
        $main_link_id = MainLink::select('id')->where('keyword', $request->section)->firstOrFail()->id;
        if ($request->full_access) {
            $user_ids = MainLinkUsers::where('links_id', $main_link_id)->select('full_access_users')->firstOrFail()->full_access_users;
        } else {
            $user_ids = MainLinkUsers::where('links_id', $main_link_id)->select('limited_access_users')->firstOrFail()->limited_access_users;
        }
        if ($user_ids === null) {
            $users_array[0] = $request->users;
        } else {
            //The second parameter should be set to true to get an array.
            $users_array = json_decode($user_ids, true);
            array_push($users_array, $request->users);
        }
        $edited_section = MainLinkUsers::where('links_id', $main_link_id)->firstOrFail();     
        if ($request->full_access) {
            $edited_section->full_access_users = json_encode($users_array);
        } else {
            $edited_section->limited_access_users = json_encode($users_array);
        }
        $edited_section->save();
    }
}
