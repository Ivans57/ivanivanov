<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use App\User;
use App\UserAlbums;
use App\Album;
use App\MainLink;
use App\MainLinkUsers;
//use App\UsersRolesAndStatuses;
//The line below is required to make query conditions using merged table's fields.
use Illuminate\Database\Eloquent\Builder;

//This class is required to show added to some directory users names on a page.
class FullAndLimitedAccessUsersNames {
    public $full_access_users_names;
    public $limited_access_users_names;
}

//This class is required to keep full and limited access user ids.
class FullAndLimitedAccessUsersIDs {
    public $full_access_users_ids;
    public $limited_access_users_ids;
}

class AdminUsersAddEditDeleteForDirectoryRepository {
    
    //This method is required to show added to some section users names on a page.
    public function get_full_and_limited_access_users_for_page($directory_keyword) {
        
        $all_user_ids_with_all_added_albums = UserAlbums::
                    select('user_id', 'en_albums_full_access', 'en_albums_limited_access', 'ru_albums_full_access', 'ru_albums_limited_access')
                    ->orderBy('user_id', 'asc')->get();
        
        $full_and_limited_access_users_names = new FullAndLimitedAccessUsersNames();
        
        $full_and_limited_access_users_names->full_access_users_names = [];
        
        $full_and_limited_access_users_names->limited_access_users_names = [];
                           
        return $full_and_limited_access_users_names;       
    }
    
    public function get_users_for_add_for_directory($directory_keyword, $section) {
        //First of all need to make a list of all users which are already added to some particular directory.
        $full_and_limited_access_users_for_directory = $this->get_full_and_limited_access_users_ids_for_directory($directory_keyword, $section);
                
        $all_users = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
                                                                                $query->where('role', '=', 'user');
                                                                                $query->where('status', '=', '1');
                                                                                })->orderBy('name', 'asc')->get();
        
        $not_added_user_names = [];
        
        foreach ($all_users as $user) {
            if ((in_array($user->id, $full_and_limited_access_users_for_directory->full_access_users_ids) === false) && 
                (in_array($user->id, $full_and_limited_access_users_for_directory->limited_access_users_ids) === false)) {
                    $not_added_user_names[$user->id] = $user->name;
                }
        }
        return $not_added_user_names;
    }
    
    private function get_full_and_limited_access_users_ids_for_directory($directory_keyword, $section) {
        
        $directory_id = Album::select('id')->where('keyword', $directory_keyword)->firstOrFail();
        
        $full_and_limited_access_users_ids = new FullAndLimitedAccessUsersIDs();
        
        $full_and_limited_access_users_ids->full_access_users_ids = [];       
        $full_and_limited_access_users_ids->limited_access_users_ids = [];
               
        foreach (UserAlbums::select('user_id', 'en_albums_full_access', 'en_albums_limited_access', 'ru_albums_full_access', 
                                    'ru_albums_limited_access')->orderBy('user_id', 'asc')->get() as $user) {
            //If any user id is in limited access users field, it can be just simply added to limited access users ids array..                    
            if (in_array($directory_id->id, (((App::isLocale('en') ? $user->en_albums_limited_access : 
                                               $user->ru_albums_limited_access)) ? 
                                              (json_decode((App::isLocale('en') ? $user->en_albums_limited_access : 
                                               $user->ru_albums_limited_access), true)) : [])) === true) {
                array_push($full_and_limited_access_users_ids->limited_access_users_ids, $user->user_id);
            } else {
                //The function below will push any particular user id to full access array only if there are some conditions for that.
                //See the logic in the function.
                $full_and_limited_access_users_ids->full_access_users_ids = $this->push_to_full_access_users_ids($user, 
                                                        $full_and_limited_access_users_ids->full_access_users_ids, $directory_id->id, $section);
            }
        }
        return $full_and_limited_access_users_ids;
    }
    
    //This function is required to unclutter get_full_and_limited_access_users_ids_for_directory() function.
    private function push_to_full_access_users_ids($user, $full_access_users_ids, $directory_id, $section) {
        $current_main_links_full_access_users = MainLinkUsers::select('full_access_users')
                                    ->where('links_id', MainLink::select('id')->where('keyword', $section)->firstOrFail()->id)->firstOrFail();
        //First of all need to check the root. If any particular user is added with a full access to the root of the directory, that means 
        //this user also has a full access to this particular directory.
        if (in_array($user->user_id, (($current_main_links_full_access_users->full_access_users) ? 
                                        json_decode($current_main_links_full_access_users->full_access_users, true) : [])) === true) {
            array_push($full_access_users_ids, $user->user_id);
        //If the user is not added to the root, that means, need to check if it is associated with that particular directory.
        //Can do it just checking one particluar field in databse.
        } else {
                    
            $full_access_users_ids_from_user = (App::isLocale('en') ? $user->en_albums_full_access : 
                                                  $user->ru_albums_full_access) ? (json_decode((App::isLocale('en') ? 
                                                  $user->en_albums_full_access : $user->ru_albums_full_access), true)) : [];
                   
            if (in_array($directory_id, $full_access_users_ids_from_user) === true) {
                array_push($full_access_users_ids, $user->user_id);
            //If the user is not associated with the particular directory, that means we need to check if the directories parents 
            //are associated with that user. If at least one of the parents is associated with the user, that means that user has full access to
            //the actual directory we are considering.
            } else {
                $full_access_users_ids = $this->push_to_full_access_users_ids_by_parents($full_access_users_ids, $directory_id, 
                                                                                           $full_access_users_ids_from_user, $user->user_id);
            }
        }
        return $full_access_users_ids;
    }
    
    //This function is required to unclutter push_to_full_access_users_ids() function.
    private function push_to_full_access_users_ids_by_parents($full_access_users_names, $directory_id, $full_access_users_ids_from_user, $user_id) {
        //Check all parents and if one of them has a full access, that means the actual child folder also has a full access.
        $all_parents_ids_of_directory = $this->get_parents_id_array($directory_id, array());
        foreach ($all_parents_ids_of_directory as $parent_id_of_directory) {
            if (in_array($parent_id_of_directory, $full_access_users_ids_from_user) === true) {
                //If user has a full access to at least one of directory's parents, that means the user has full access to this directory.
                //No need to check another directories. 
                array_push($full_access_users_names, $user_id);
                break;
            }
        }
        return $full_access_users_names;
    }
    
    //This function is required to get all parents of some particular directory.
    private function get_parents_id_array($directory_id, $parents) {
        
        $parent = Album::select('included_in_album_with_id')->where('id', $directory_id)->firstOrFail();
        
        if ($parent->included_in_album_with_id) {
            array_push($parents, $parent->included_in_album_with_id);
            $parents_array = $this->get_parents_id_array($parent->included_in_album_with_id, $parents);
            return $parents_array;
        } else {
            return $parents;
        }        
    }
    
    public function join_user_for_directory($request) {
        
        $directory_id = Album::select('id')->where('keyword', $request->directory)->firstOrFail()->id;
                
        $main_link_id = MainLink::where('keyword', $request->section)->firstOrFail()->id;
        
        $current_main_link_users_data = MainLinkUsers::where('links_id', $main_link_id)->firstOrFail();
        
        $current_main_link_full_access_users_ids = json_decode($current_main_link_users_data->full_access_users, true);
        
        $current_user_data = UserAlbums::where('user_id', $request->users)->orderBy('user_id', 'asc')->firstOrFail();
        
        if (!$current_main_link_full_access_users_ids) {
            $current_main_link_full_access_users_ids = [];
        }
        
        if (!$current_main_link_full_access_users_ids || (in_array($request->users, $current_main_link_full_access_users_ids) === false)) {
            if (App::isLocale('en') && $request->full_access) {
                //The variable below is required to provide a limited access for a root directory.
                $current_main_link_limited_access_users_ids = json_decode($current_main_link_users_data->limited_access_users, true);
                $albums_ids_array = json_decode($current_user_data->en_albums_full_access, true);
                $all_parents_ids_of_directory = $this->get_parents_id_array($directory_id, array());
                
                //!!Need to revise some conditions below. Something can be joined, condtions might be only for some particular things.
                if ($albums_ids_array && (in_array($directory_id, $albums_ids_array) === false)) {                    
                    if (!$all_parents_ids_of_directory || ($this->parent_has_full_access($all_parents_ids_of_directory, 
                                                                                                    $albums_ids_array) === false)) {
                        array_push($albums_ids_array, (string)$directory_id);
                        //All parents of full access folder (if they exist) should have limited access if they don't have it.
                        if ($all_parents_ids_of_directory) {
                            $limited_access_albums_ids_array = $this->push_parent_to_limited_access($all_parents_ids_of_directory, 
                                                               (json_decode($current_user_data->en_albums_limited_access, true)));
                        } /*Need to add user to limited_access_users for main links.*/else {
                            if (in_array($request->users, $current_main_link_limited_access_users_ids) === false) {
                                array_push($current_main_link_limited_access_users_ids, (string)$request->users);                              
                                $current_main_link_users_data->limited_access_users = json_encode($current_main_link_limited_access_users_ids);
                                $current_main_link_users_data->save();
                            }
                        }
                    }
                } else if (!$albums_ids_array) {
                    $albums_ids_array = [];
                    array_push($albums_ids_array, (string)$directory_id);
                    //All parents of full access folder should have limited access if they don't have it.
                    if ($all_parents_ids_of_directory) {
                        $limited_access_albums_ids_array = $this->push_parent_to_limited_access($all_parents_ids_of_directory, 
                                                           (json_decode($current_user_data->en_albums_limited_access, true)));
                    } /*Need to add user to limited_access_users for main links.*/else {
                        if (in_array($request->users, $current_main_link_limited_access_users_ids) === false) {
                            array_push($current_main_link_limited_access_users_ids, (string)$request->users);
                            $current_main_link_users_data->limited_access_users = json_encode($current_main_link_limited_access_users_ids);
                            $current_main_link_users_data->save(); 
                        }
                    }
                }
                $current_user_data->en_albums_full_access = json_encode($albums_ids_array);
                //There will be changes in limited albums field only if there are some parents for changed album.
                if ($all_parents_ids_of_directory) {
                    $current_user_data->en_albums_limited_access = json_encode($limited_access_albums_ids_array);
                }
            } else if (App::isLocale('en') && !$request->full_access) {
                $albums_ids_array = json_decode($current_user_data->en_albums_limited_access, true);
                if ($albums_ids_array && (in_array($directory_id, $albums_ids_array) === false)) {
                    array_push($albums_ids_array, (string)$directory_id);
                } else if (!$albums_ids_array) {
                    $albums_ids_array = [];
                    array_push($albums_ids_array, (string)$directory_id);
                }
                $current_user_data->en_albums_limited_access = json_encode($albums_ids_array);
            } else if (App::isLocale('ru') && $request->full_access) {
                $albums_ids_array = json_decode($current_user_data->ru_albums_full_access, true);

                if ($albums_ids_array && (in_array($directory_id, $albums_ids_array) === false)) {
                    $all_parents_ids_of_directory = $this->get_parents_id_array($directory_id, array());
                    if ($this->parent_has_full_access($all_parents_ids_of_directory, $albums_ids_array) === false) {
                        array_push($albums_ids_array, (string)$directory_id);
                    }
                } else if (!$albums_ids_array) {
                    $albums_ids_array = [];
                    array_push($albums_ids_array, (string)$directory_id);
                }
            $current_user_data->ru_albums_full_access = json_encode($albums_ids_array);
            } else if (App::isLocale('ru') && !$request->full_access) {
                $albums_ids_array = json_decode($current_user_data->ru_albums_limited_access, true);
                if ($albums_ids_array && (in_array($directory_id, $albums_ids_array) === false)) {
                    array_push($albums_ids_array, (string)$directory_id);
                } else if (!$albums_ids_array) {
                    $albums_ids_array = [];
                    array_push($albums_ids_array, (string)$directory_id);
                }
                $current_user_data->ru_albums_full_access = json_encode($albums_ids_array);
            }       
            $current_user_data->save();
        }
    }
    
    //This function is required to push ids of parents of album which is getting full access status to limited access albums, so
    //user can have a full path to that full access album.
    private function push_parent_to_limited_access($all_parents_ids, $limited_access_albums_ids_array) {
        if ($limited_access_albums_ids_array) {
            foreach ($all_parents_ids as $parent_id) {
                if (in_array($parent_id, $limited_access_albums_ids_array) === false) {
                    array_push($limited_access_albums_ids_array, (string)$parent_id);
                }
            }
        } else {
            foreach ($all_parents_ids as $parent_id) {
                array_push($limited_access_albums_ids_array, (string)$parent_id);
            }
        }
        return $limited_access_albums_ids_array;
    }
    
    private function parent_has_full_access($parents_ids, $albums_ids_array) {
        foreach ($parents_ids as $parents_id) {
            if (in_array($parents_id, $albums_ids_array) === true) {
                return true;
            }
        }
        return false;
    }

}
