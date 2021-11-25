<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use App\MainLink;
use App\MainLinkUsers;

//We need CommonRepository to provide uniqueness checks.
use App\Http\Repositories\ValidationRepository;


class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
         
        Validator::extend('prohibited_characters', function ($attribute, $value, $parameters, $validator) {
            $allowed_characters = (new ValidationRepository())->get_allowed_characters($parameters[0]);
            $characters_to_check = str_split($value, 1);
            foreach ($characters_to_check as $character_to_check) {
                if (in_array($character_to_check, $allowed_characters)) {
                } else {
                    return false;
                }
            }
            return true;
        });
             
        Validator::extend('space_check', function ($attribute, $value, $parameters, $validator) {
            $pieces = explode(" ", $value);
            
            if (count($pieces) > 1) {
                return false;
            } else {
                return true;
            }
        });   
        
        Validator::extend('uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[1])) == 0) {
                return true;
            } else {
                return (new ValidationRepository())->uniqueness_check_for_validation($parameters[0], $value);
            }
        });
        
        
        //This validation is required to check whether an user id attempted to be saved in main_links_users table already exists in there.
        Validator::extend('user_id_uniqueness_check_for_add', function ($attribute, $value, $parameters, $validator) {
            
            $main_link_id = MainLink::select('id')->where('keyword', $parameters[0])->firstOrFail()->id;
            
            //Need to check in two fields at the same time, so the same id could not be written in two different fields.
            $saved_user_ids_full_access = MainLinkUsers::where('links_id', $main_link_id)->select('full_access_users')
                                                         ->firstOrFail()->full_access_users;
            $saved_user_ids_limited_access = MainLinkUsers::where('links_id', $main_link_id)->select('limited_access_users')
                                                            ->firstOrFail()->limited_access_users;
            //The line below will work in case full and limited access user ids are both null (empty).
            $saved_user_ids = null;
            
            if ($saved_user_ids_full_access === null && $saved_user_ids_limited_access != null) {
                $saved_user_ids = $saved_user_ids_limited_access;
                $saved_user_ids_array = json_decode($saved_user_ids, true);
            } else if ($saved_user_ids_limited_access === null && $saved_user_ids_full_access != null) {
                $saved_user_ids = $saved_user_ids_full_access;
                $saved_user_ids_array = json_decode($saved_user_ids, true);
            } else if ($saved_user_ids_full_access != null && $saved_user_ids_limited_access != null) {
                //The line below is required to make the condition below to be executed.
                $saved_user_ids = 1;
                $saved_user_ids_full_access_array = json_decode($saved_user_ids_full_access, true);
                $saved_user_ids_limited_access_array = json_decode($saved_user_ids_limited_access, true);
                $saved_user_ids_array = array_merge($saved_user_ids_full_access_array, $saved_user_ids_limited_access_array);
            }
            
            if ($saved_user_ids && (in_array($value, $saved_user_ids_array) == true)) {
                return false;
            } else {
                return true;
            }           
        });
        
        Validator::extend('item_has_directory', function ($attribute, $value, $parameters, $validator) {
            //It is not allowed to store any picture in a root album (out of any album).
            //Below is a check for it. 
            if ($value == 0) {
                return false;
            } else {
                return true;
            }
        });
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
