<?php

namespace App\Http\Repositories;

use App\User;
use App\MainLink;
use App\MainLinkUsers;
//The line below is required to make query conditions using merged table's fields.
use Illuminate\Database\Eloquent\Builder;


//This for updating user access.
class UserNameAndAccess {
    public $name;
    public $access;
}

class AdminUsersAddEditDeleteRepository {
    
    //!Need somehow to merge or simplify add and edit methods, extracting the same parts of code!
    public function get_users_for_add_for_albums($section) {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'asc')->get();

        //I will extract full_access_users and limited_access_users as shown below, because there is a confusion with field names when 
        //doing with model relations.
        $main_link_id = MainLink::select('id')->where('keyword', $section)->firstOrFail()->id;
        $full_and_limited_access_user_ids = MainLinkUsers::where('links_id', $main_link_id)
                                            ->select('full_access_users', 'limited_access_users')->firstOrFail();
        $users = array();
                
        foreach ($users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode($full_and_limited_access_user_ids->full_access_users, true), 
                                             json_decode($full_and_limited_access_user_ids->limited_access_users, true))) == false) {
                $users[$user_from_database->id] = $user_from_database->name;
            }
        }
        return $users;
    }
    
    //!Need somehow to merge or simplify add and edit methods, extracting the same parts of code!
    public function get_users_for_edit_for_albums($section) {
        
        $users_from_database = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
            $query->where('role', '=', 'user'); 
        })->orderBy('name', 'asc')->get();

        //I will extract full_access_users and limited_access_users as shown below, because there is a confusion with field names when 
        //doing with model relations.
        $main_link_id = MainLink::select('id')->where('keyword', $section)->firstOrFail()->id;
        $full_and_limited_access_user_ids = MainLinkUsers::where('links_id', $main_link_id)
                                            ->select('full_access_users', 'limited_access_users')->firstOrFail();
        $user_names_and_accesses = array();
        
        foreach ($users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode($full_and_limited_access_user_ids->full_access_users, true), 
                                             json_decode($full_and_limited_access_user_ids->limited_access_users, true))) == true) {
                $user_name_and_access = new UserNameAndAccess();
                $user_name_and_access->name = $user_from_database->name;
                $user_name_and_access->access = $this->get_user_access($user_from_database->id, 
                                                                json_decode($full_and_limited_access_user_ids->full_access_users, true), 
                                                                json_decode($full_and_limited_access_user_ids->limited_access_users, true));
                $user_names_and_accesses[$user_from_database->id] = $user_name_and_access;
            }
        }     
        return $user_names_and_accesses;
    }
    
    //!Need to check with the second and the third parameters as nulls!
    //The method below is required to get an information whether some particular user has limited or full access to some section
    //it has been added to.
    private function get_user_access($user_id, $full_access_user_ids, $limited_access_user_ids) {
        
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
