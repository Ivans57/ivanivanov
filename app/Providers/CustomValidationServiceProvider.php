<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
