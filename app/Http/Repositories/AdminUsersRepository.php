<?php

namespace App\Http\Repositories;

//use Carbon\Carbon;
use App\User;
use App\UsersRolesAndStatuses;
use App\Http\Repositories\CommonRepository;
use Illuminate\Support\Facades\DB;

class AdminUsersRepository {
    
    public function store($request) {
        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        
        $user_role_and_status = new UsersRolesAndStatuses;
        $user_role_and_status->role = 'user';     
        $user_role_and_status->status = $request->status;
        $user->role_and_status()->save($user_role_and_status);
    }
    
    public function update($name, $request) {
             
        $edited_user = User::where('name', '=', $name)->firstOrFail();     
        $edited_user->name = $request->name;
        $edited_user->email = $request->email;   
        //Need to assign field password only if there is a new password, otherwise can skip it.
        if ($request->password) {
            $edited_user->password = bcrypt($request->password);
        }
        $edited_user->save();
        
        $edited_user_role_and_status = UsersRolesAndStatuses::where('user_id', '=', $edited_user->id)->firstOrFail();
        $edited_user_role_and_status->status = $request->status;
        $edited_user_role_and_status->save();
    }
    
    public function destroy($usernames) {
        $usernames_array = (new CommonRepository())->get_values_from_string($usernames);
        
        foreach ($usernames_array as $username) {
            $user_id = User::where('name', '=', $username)->firstOrFail()->id;
            
            //User with admin status should be unable to be deleted.
            if (UsersRolesAndStatuses::where('user_id', '=', $user_id)->firstOrFail()->role == 'user') {
                $this->remove_user_id_from_main_links($user_id, DB::table('en_main_links_users')->get(), 'en_main_links_users');
                $this->remove_user_id_from_main_links($user_id, DB::table('ru_main_links_users')->get(), 'ru_main_links_users');
                User::where('name', '=', $username)->delete();
            }
        }
    }
    
    //This method removes being deleted user's id from all sections (main links) where it has been added to.
    private function remove_user_id_from_main_links($user_id_to_remove, $main_links_info, $table_to_update) {
        foreach ($main_links_info as $main_link_info) {
            if ($main_link_info->full_access_users) {
                $this->remove_user_id_from_main_link($user_id_to_remove, $main_link_info->full_access_users, $main_link_info->links_id, 
                                                     $table_to_update, 'full_access_users');
            }
            if ($main_link_info->limited_access_users) {
                $this->remove_user_id_from_main_link($user_id_to_remove, $main_link_info->limited_access_users, $main_link_info->links_id, 
                                                     $table_to_update, 'limited_access_users');
            }
        }
    }
    
    //This method is required to simplify remove_user_id_from_main_links() method.
    private function remove_user_id_from_main_link($user_id_to_remove, $added_users_ids, $edited_link_id, $table_to_update, $field_to_update) {

        $added_user_ids_array = json_decode($added_users_ids, true);
        if (in_array($user_id_to_remove, $added_user_ids_array) == true) {
            unset($added_user_ids_array[array_search($user_id_to_remove, $added_user_ids_array)]);
            //Below an array from where updated user was extracted is getting reindexed. If we don't do that, we will get wrong json in the end.
            $added_user_ids_array_edited = array_values($added_user_ids_array);

            //Now need to update information in database.
            //If we remove the last value from the array, we don't need an empty array, it should become null.
            DB::table($table_to_update)->where('links_id', $edited_link_id)
                                       ->update([$field_to_update => sizeof($added_user_ids_array_edited) == 0 ? 
                                                                            null : json_encode($added_user_ids_array_edited)]);
        }

    }
    
    //The method below is to sort users in different sorting modes.
    public function sort($sorting_mode) {
        //This array is required to show sorting arrows properly.
        $sorting_asc_or_desc = ["Name" => ["desc" , 0], "Email" => ["desc" , 0], "Creation" => ["desc" , 0], "Update" => ["desc" , 0], 
                                "Role" => ["desc" , 0], "Status" => ["desc" , 0],];             
        $users = null;
        
        switch ($sorting_mode) {
            case ('users_sort_by_name_desc'):         
                $users = (User::with('role_and_status')->orderBy('Name', 'desc')->get());      
                $sorting_asc_or_desc["Name"] = ["asc" , 1];        
                break;
            
            case ('users_sort_by_name_asc'):          
                $users = (User::with('role_and_status')->orderBy('Name', 'asc')->get());            
                $sorting_asc_or_desc["Name"] = ["desc" , 1];
                break;
            
            case ('users_sort_by_email_desc'):   
                $users = (User::with('role_and_status')->orderBy('Email', 'desc')->get());                
                $sorting_asc_or_desc["Email"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_email_asc'):
                $users = (User::with('role_and_status')->orderBy('Email', 'asc')->get());            
                $sorting_asc_or_desc["Email"] = ["desc" , 1];
                break;
            
            case ('users_sort_by_creation_desc'):
                $users = (User::with('role_and_status')->latest()->get());             
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_creation_asc'):
                $users = (User::with('role_and_status')->orderBy('created_at', 'asc')->get());                
                $sorting_asc_or_desc["Creation"] = ["desc" , 1];
                break;
            
            case ('users_sort_by_update_desc'):
                $users = (User::with('role_and_status')->orderBy('updated_at', 'desc')->get());           
                $sorting_asc_or_desc["Update"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_update_asc'):
                $users = (User::with('role_and_status')->orderBy('updated_at', 'asc')->get());              
                $sorting_asc_or_desc["Update"] = ["desc" , 1];
                break; 
            
            case ('users_sort_by_role_desc'):
                $users = User::with('role_and_status')->get()->sortByDesc('role_and_status.role');
                $sorting_asc_or_desc["Role"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_role_asc'):
                //for sort bu ascending don't need to specify it is ascending, only for descending like above.
                $users = User::with('role_and_status')->get()->sortBy('role_and_status.role');
                $sorting_asc_or_desc["Role"] = ["desc" , 1];
                break;
            
            case ('users_sort_by_status_desc'):
                $users = User::with('role_and_status')->get()->sortByDesc('role_and_status.status');          
                $sorting_asc_or_desc["Status"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_status_asc'):
                $users = User::with('role_and_status')->get()->sortBy('role_and_status.status');          
                $sorting_asc_or_desc["Status"] = ["desc" , 1];
                break;
            
            default:
                $users = (User::with('role_and_status')->latest()->get());      
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];   
        }     
        return ["users" => $users, "sorting_asc_or_desc" => $sorting_asc_or_desc];
    }
    
    //We need this to make a check for email uniqueness.
    public function get_all_names_or_emails($names_or_emails) {
             
        $all_names_or_emails = User::all();      
        $names_or_emails_array = array();
        
        foreach ($all_names_or_emails as $name_or_email) {
            array_push($names_or_emails_array, ($names_or_emails == 'username' ? $name_or_email->name : $name_or_email->email));
        }    
        return $names_or_emails_array;
    }
}
