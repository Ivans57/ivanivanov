<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        Validator::extend('folder_keyword_pattern', function ($attribute, $value, $parameters, $validator) {
            $allowed_characters = array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", 
            "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $characters_to_check = str_split($value, 1);
            foreach ($characters_to_check as $character_to_check) {
                if (in_array($character_to_check, $allowed_characters)) {
                    //echo "Got Irix";
                } else {
                    return false;
                }
            }
            return true;
        });
        
        Validator::replacer('folder_keyword_pattern', function($message, $attribute, $rule, $parameters) {
            return str_replace($message, __("customValidation.prohibited_characters"), $message);
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
