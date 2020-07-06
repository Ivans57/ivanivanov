<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

//We need AlbumsRepository to provide Album Keyword's uniqueness check
use App\Http\Repositories\AlbumsRepository;
//We need ArticlesRepository to provide Folder Keyword's uniqueness check
use App\Http\Repositories\ArticlesRepository;
//We need ArticlesRepository to provide Keyword's uniqueness check
use App\Http\Repositories\KeywordsRepository;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        Validator::extend('prohibited_characters', function ($attribute, $value, $parameters, $validator) {
            //I have added space to the array of allowed characters only for the purpose to show an error message
            //just once, so we don't have two messages for prohibited characters and for spaces detection.
            //We just need only one message telling that spaces are not allowed.
            $allowed_characters = array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", 
            "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", 
                "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
            $characters_to_check = str_split($value, 1);
            foreach ($characters_to_check as $character_to_check) {
                if (in_array($character_to_check, $allowed_characters)) {
                } else {
                    return false;
                }
            }
            return true;
        });
        
        Validator::extend('keywords_prohibited_characters', function ($attribute, $value, $parameters, $validator) {
            //I have added space to the array of allowed characters only for the purpose to show an error message
            //just once, so we don't have two messages for prohibited characters and for spaces detection.
            //We just need only one message telling that spaces are not allowed.
            $allowed_characters = array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", 
            "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", 
                "X", "x", "Y", "y", "Z", "z", " ");
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
        
        Validator::extend('album_keyword_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $albums = new AlbumsRepository();
                $all_keywords = $albums->get_all_albums_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('folder_keyword_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $folders = new ArticlesRepository();
                $all_keywords = $folders->get_all_folders_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('keyword_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $keywords = new KeywordsRepository();
                $all_keywords = $keywords->get_all_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
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
