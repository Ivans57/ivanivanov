<?php

namespace App\Http\Repositories;

//use App\User;
use App\UserAlbums;
//use App\MainLinkUsers;
//use App\UsersRolesAndStatuses;
//The line below is required to make query conditions using merged table's fields.
//use Illuminate\Database\Eloquent\Builder;

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
}
