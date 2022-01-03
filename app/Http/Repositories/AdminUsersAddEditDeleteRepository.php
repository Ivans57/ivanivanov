<?php

namespace App\Http\Repositories;

use App\User;
use App\MainLink;
use App\MainLinkUsers;
use App\UsersRolesAndStatuses;
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

//This class is required to show added to some section users names on a page.
class FullAndLimitedAccessUsersNames {
    public $full_access_users_names;
    public $limited_access_users_names;
}

class AdminUsersAddEditDeleteRepository {
    
    //This method is required to show added to some section users names on a page.
    public function get_full_and_limited_access_users_for_page($section) {
        
        $full_and_limited_access_user_ids = MainLinkUsers::where('links_id', 
                                                                 MainLink::select('id')->where('keyword', $section)->firstOrFail()->id)
                                                                 ->select('full_access_users', 'limited_access_users')->firstOrFail();
                                                                
                           
        return $this->get_full_and_limited_access_users_names(
                                                     json_decode($full_and_limited_access_user_ids->full_access_users, true), 
                                                     json_decode($full_and_limited_access_user_ids->limited_access_users, true));       
    }
    
    //This method is required to simplify get_full_and_limited_access_users_for_page() method.
    private function get_full_and_limited_access_users_names($full_access_user_ids, $limited_access_user_ids) {
              
        $full_and_limited_access_user_names = new FullAndLimitedAccessUsersNames();      
        $full_and_limited_access_user_names->full_access_users_names = [];
        $full_and_limited_access_user_names->limited_access_users_names = [];
        
        if ($full_access_user_ids) {
            foreach ($full_access_user_ids as $full_access_user_id) {
                //Only active users need to show.
                if ((UsersRolesAndStatuses::select('status')->where('user_id', $full_access_user_id)->firstOrFail()->status) == 1) {
                    array_push($full_and_limited_access_user_names->full_access_users_names, 
                               User::select('name')->where('id', $full_access_user_id)->firstOrFail()->name);
                }
            }
        }        
        if ($limited_access_user_ids) {       
            foreach ($limited_access_user_ids as $limited_access_user_id) {
                //Only active users need to show.
                if ((UsersRolesAndStatuses::select('status')->where('user_id', $limited_access_user_id)->firstOrFail()->status) == 1) {
                    array_push($full_and_limited_access_user_names->limited_access_users_names, 
                               User::select('name')->where('id', $limited_access_user_id)->firstOrFail()->name);
                }
            }
        }    
        return $full_and_limited_access_user_names;       
    }
    
    public function get_users_for_add_for_section($section) {
        
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
    
    public function get_users_for_edit_for_section($section) {
        
        $users_from_database_and_added_users_ids = $this->get_users_from_database_and_added_users_ids($section);
        
        $user_names_and_accesses = array();       
        foreach ($users_from_database_and_added_users_ids->users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode($users_from_database_and_added_users_ids
                                                         ->full_and_limited_access_user_ids->full_access_users, true), 
                                             json_decode($users_from_database_and_added_users_ids
                                                         ->full_and_limited_access_user_ids->limited_access_users, true))) == true) {
                $user_name_and_access = new UserNameAndAccess();
                $user_name_and_access->name = $user_from_database->name;
                $user_name_and_access->access = $this->get_user_access($user_from_database->id, 
                                                                json_decode($users_from_database_and_added_users_ids
                                                                            ->full_and_limited_access_user_ids->full_access_users, true), 
                                                                json_decode($users_from_database_and_added_users_ids
                                                                            ->full_and_limited_access_user_ids->limited_access_users, true));
                $user_names_and_accesses[$user_from_database->id] = $user_name_and_access;
            }
        }     
        return $user_names_and_accesses;
    }
    
    public function get_users_for_delete_for_section($section) {
        
        $users_from_database_and_added_users_ids = $this->get_users_from_database_and_added_users_ids($section);
        
        $users = array();               
        foreach ($users_from_database_and_added_users_ids->users_from_database as $user_from_database) {
            if (in_array($user_from_database->id, 
                         $this->get_user_ids(json_decode(
                                             $users_from_database_and_added_users_ids->full_and_limited_access_user_ids->full_access_users, 
                                             true), 
                                             json_decode(
                                             $users_from_database_and_added_users_ids->full_and_limited_access_user_ids->limited_access_users, 
                                             true))) == true) {
                $users[$user_from_database->id] = $user_from_database->name;
            }
        }
        return $users;
    }
    
    //The function below is required to tick the checkbox properly according to the acees status 
    //of the first user in dropdown list.
    public function get_status_of_first_user_for_section($users_and_accesses) {
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
                                                                                $query->where('status', '=', '1');
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
    
    public function join_user_for_section($request) {
        
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
    
    public function update_user_for_section($request) {
             
        $getting_updated_link = MainLinkUsers::where('links_id', MainLink::select('id')
                                               ->where('keyword', $request->section)->firstOrFail()->id)->firstOrFail();
        
        $full_access_user_ids_array = $getting_updated_link->full_access_users ? 
                                      json_decode($getting_updated_link->full_access_users, true) : [];
        
        $limited_access_user_ids_array = $getting_updated_link->limited_access_users ? 
                                      json_decode($getting_updated_link->limited_access_users, true) : [];
        
        //Sometimes admin-user can try to update selected user with the status that getting updated user already has.
        //If it happens, database record update will be skipped (see conditions below).
        //The variable below is required, because id arrays will change during this function execution.
        $changing_user_already_has_required_status = $request->full_access ? in_array($request->users, $full_access_user_ids_array) : 
                                                                             in_array($request->users, $limited_access_user_ids_array);
        
        //For the first two cases also need to consider the second condition, otherwise this function will work wrong.
        //In that wrong case it will just execute another condition which always will be satisfactory.
        if ($changing_user_already_has_required_status === false && $request->full_access) {
            
            array_push($full_access_user_ids_array, $request->users/*changing user id*/);
            //Below we are extracting updated user's id from previous status array.
            unset($limited_access_user_ids_array[array_search($request->users/*changing user id*/, $limited_access_user_ids_array)]);
            //Below an array from where updated user was extracted is getting reindexed. If we don't do that, we will get wrong json in the end.
            $limited_access_user_ids_array = array_values($limited_access_user_ids_array);
            
        } else if ($changing_user_already_has_required_status === false && !$request->full_access) {
            array_push($limited_access_user_ids_array, $request->users/*changing user id*/);
            //Below we are extracting updated user's id from previous status array.
            unset($full_access_user_ids_array[array_search($request->users/*changing user id*/, $full_access_user_ids_array)]);
            //Below an array from where updated user was extracted is getting reindexed. If we don't do that, we will get wrong json in the end.
            $full_access_user_ids_array = array_values($full_access_user_ids_array);
        }
        if ($changing_user_already_has_required_status === false) {
            
            //The line below saves all changes in database.
            $this->update_main_link_user_ids($getting_updated_link, $full_access_user_ids_array, $limited_access_user_ids_array);
            
        }
    }
    
    //The function below saves all related to users changes for main links in database.
    //I made it to shorten main update (update_user_for_albums()) method.
    private function update_main_link_user_ids($getting_updated_link, $full_access_user_ids_array, $limited_access_user_ids_array) {
        
        $getting_updated_link->full_access_users = sizeof($full_access_user_ids_array) == 0 ? 
                                                       null : json_encode($full_access_user_ids_array);
            
        $getting_updated_link->limited_access_users = sizeof($limited_access_user_ids_array) == 0 ? 
                                                       null : json_encode($limited_access_user_ids_array);
            
        $getting_updated_link->save();
    }
    
    public function destroy_user_for_section($request) {
             
        $getting_updated_link = MainLinkUsers::where('links_id', MainLink::select('id')
                                               ->where('keyword', $request->section)->firstOrFail()->id)->firstOrFail();
        
        $full_access_user_ids_array = $getting_updated_link->full_access_users ? 
                                      json_decode($getting_updated_link->full_access_users, true) : [];
        
        $limited_access_user_ids_array = $getting_updated_link->limited_access_users ? 
                                      json_decode($getting_updated_link->limited_access_users, true) : [];
        
        $user_access_status/*limited or full*/ = $this->get_user_access($request->users/*deleted user's id*/, 
                                                                        $full_access_user_ids_array, $limited_access_user_ids_array);
        
        if ($user_access_status == 'full') {
            unset($full_access_user_ids_array[array_search($request->users, $full_access_user_ids_array)]);
            //Below an array from where updated user was extracted is getting reindexed. If we don't do that, we will get wrong json in the end.
            $full_access_user_ids_array = array_values($full_access_user_ids_array);
        } else if ($user_access_status == 'limited') {
            unset($limited_access_user_ids_array[array_search($request->users, $limited_access_user_ids_array)]);
            //Below an array from where updated user was extracted is getting reindexed. If we don't do that, we will get wrong json in the end.
            $limited_access_user_ids_array = array_values($limited_access_user_ids_array);
        }
        
        //The line below saves all changes in database.
        $this->update_main_link_user_ids($getting_updated_link, $full_access_user_ids_array, $limited_access_user_ids_array);
    }
}
