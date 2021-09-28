<?php

namespace App\Http\Repositories;

//use Carbon\Carbon;
use App\User;
use App\UsersRolesAndStatuses;


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
        $edited_user->password = bcrypt($request->password);
        $edited_user->save();
        
        $edited_user_role_and_status = UsersRolesAndStatuses::where('user_id', '=',  $edited_user->id)->firstOrFail();
        $edited_user_role_and_status->status = $request->status;
        $edited_user_role_and_status->save();
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
    
    //We need this to make a check for username uniqueness.
    public function get_all_names() {
        
        $all_names = User::all('name');       
        $names_array = array();
        
        foreach ($all_names as $name) {
            array_push($names_array, $name->name);
        }    
        return $names_array;   
    }
    
    //We need this to make a check for email uniqueness.
    public function get_all_emails() {
        
        $all_emails = User::all('email');       
        $emails_array = array();
        
        foreach ($all_emails as $email) {
            array_push($emails_array, $email->email);
        }    
        return $emails_array;   
    }
}
