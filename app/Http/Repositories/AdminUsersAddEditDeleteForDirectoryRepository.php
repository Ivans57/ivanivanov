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
    
    public function get_users_for_add_for_directory($directory_keyword) {
        
        $full_and_limited_access_users_for_directory = $this->get_full_and_limited_access_users_ids_for_directory($directory_keyword);
                
        $all_users = User::select('id', 'name')->with('role_and_status')->whereHas('role_and_status', function (Builder $query) { 
                                                                                $query->where('role', '=', 'user');
                                                                                $query->where('status', '=', '1');
                                                                                })->orderBy('name', 'asc')->get();
        
        $not_added_user_names = [];
        
        foreach ($all_users as $user) {
            if ((in_array($user->id, $full_and_limited_access_users_for_directory->full_access_users_names) === false) || 
                (in_array($user->id, $full_and_limited_access_users_for_directory->limited_access_users_names) === false)) {
                    array_push($not_added_user_names, $user->name);
                }
        }
        return $not_added_user_names;
    }
    
    private function get_full_and_limited_access_users_ids_for_directory($directory_keyword) {
        
        $directory_id = Album::select('id')->where('keyword', $directory_keyword)->firstOrFail();
        
        $all_user_ids_with_all_added_albums = UserAlbums::
                    select('user_id', 'en_albums_full_access', 'en_albums_limited_access', 'ru_albums_full_access', 'ru_albums_limited_access')
                    ->orderBy('user_id', 'asc')->get();
        
        $full_and_limited_access_users_names = new FullAndLimitedAccessUsersNames();
        
        $full_and_limited_access_users_names->full_access_users_names = [];
        
        $full_and_limited_access_users_names->limited_access_users_names = [];
        
        foreach ($all_user_ids_with_all_added_albums as $user_id) {
            $limited_access_users_names_from_user = json_decode((App::isLocale('en') ? $user_id->en_albums_limited_access : 
                                                                                       $user_id->ru_albums_limited_access), true);

            if ($limited_access_users_names_from_user === null) {
                $limited_access_users_names_from_user = [];
            }
            
            if (in_array($directory_id, $limited_access_users_names_from_user) === true) {
                array_push($full_and_limited_access_users_names->limited_access_users_names, $user_id->user_id);
            } else {
                $current_main_link_id = MainLink::select('id')->where('keyword', 'Albums')->firstOrFail();
                $current_main_links_full_access_users = MainLinkUsers::select('full_access_users')
                                                        ->where('links_id', $current_main_link_id->id)->firstOrFail();
                $current_main_links_full_access_users_array = json_decode($current_main_links_full_access_users->full_access_users, true);
                
                if($current_main_links_full_access_users_array === null) {
                    $current_main_links_full_access_users_array = [];
                }
                
                if (in_array($user_id->user_id, $current_main_links_full_access_users_array) === true) {
                    array_push($full_and_limited_access_users_names->full_access_users_names, $user_id->user_id);
                } else {
                    $full_access_users_names_from_user = json_decode((App::isLocale('en') ? $user_id->en_albums_full_access : 
                                                                                            $user_id->ru_albums_full_access), true);
                    
                    if ($full_access_users_names_from_user === null) {
                        $full_access_users_names_from_user = [];
                    }
                    
                    if (in_array($directory_id, $full_access_users_names_from_user) === true) {
                        array_push($full_and_limited_access_users_names->full_access_users_names, $user_id->user_id);
                    } else {
                        //Check all parents.
                        $all_parents_ids_of_directory = $this->get_parents_id_array($directory_id->id, array());
                        foreach ($all_parents_ids_of_directory as $parent_id_of_directory) {
                            if (in_array($parent_id_of_directory, $full_access_users_names_from_user) === true) {
                                //If user has a full access to at least one of directory's parents, that means the user has full access to this directory.
                                //No need to check another directories. 
                                array_push($full_and_limited_access_users_names->full_access_users_names, $user_id->user_id);
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $full_and_limited_access_users_names;
    }
    
    //This function is required to get all parents of some particular directory.
    private function get_parents_id_array($directory_id, $parents) {
        
        $parent_id = Album::select('included_in_album_with_id')->where('id', $directory_id)->firstOrFail()->id;
        
        if ($parent_id) {
            array_push($parents, $parent_id->included_in_album_with_id);
            $parents_array = $this->get_parents_id_array($parent_id->included_in_album_with_id, $parents);
            return $parents_array;
        } else {
            return $parents;
        }
        
    }

}
