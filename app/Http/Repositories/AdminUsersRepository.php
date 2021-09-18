<?php

namespace App\Http\Repositories;

//use Carbon\Carbon;
use App\User;


class AdminUsersRepository {
    
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
                $users = (User::with('role_and_status')->latest()->get());              
                $sorting_asc_or_desc["Role"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_role_asc'):
                $users = (User::with('role_and_status')->orderBy('role', 'asc')->get());      
                $sorting_asc_or_desc["Role"] = ["desc" , 1];
                break;
            
            case ('users_sort_by_status_desc'):
                $users = (User::with('role_and_status')->orderBy('status', 'desc')->get());           
                $sorting_asc_or_desc["Status"] = ["asc" , 1];
                break;
            
            case ('users_sort_by_status_asc'):
                $users = (User::with('role_and_status')->orderBy('status', 'asc')->get());           
                $sorting_asc_or_desc["Status"] = ["desc" , 1];
                break;
            
            default:
                $users = (User::with('role_and_status')->latest()->get());      
                $sorting_asc_or_desc["Creation"] = ["asc" , 1];   
        }     
        return ["users" => $users, "sorting_asc_or_desc" => $sorting_asc_or_desc];
    }
}
