<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

//We need AlbumsRepository to provide Album Keyword's uniqueness check.
use App\Http\Repositories\AlbumsRepository;
//We need ArticlesRepository to provide Folder Keyword's uniqueness check.
use App\Http\Repositories\ArticlesRepository;
//We need ArticlesRepository to provide Keyword's uniqueness check.
use App\Http\Repositories\KeywordsRepository;
//We need AlbumsRepository to provide Picture Keyword's uniqueness check.
use App\Http\Repositories\AdminPicturesRepository;
//We need AdminArticleRepository to provide Articles Keyword's uniqueness check.
use App\Http\Repositories\AdminArticleRepository;
//We need AdminUsersRepository to provide Username and Email uniqueness check.
use App\Http\Repositories\AdminUsersRepository;

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
        
        Validator::extend('users_prohibited_characters', function ($attribute, $value, $parameters, $validator) {
            //I have added space to the array of allowed characters only for the purpose to show an error message
            //just once, so we don't have two messages for prohibited characters and for spaces detection.
            //We just need only one message telling that spaces are not allowed.
            $allowed_characters = array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", 
            "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", 
                "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "_");
            $characters_to_check = str_split($value, 1);
            foreach ($characters_to_check as $character_to_check) {
                if (in_array($character_to_check, $allowed_characters)) {
                } else {
                    return false;
                }
            }
            return true;
        });
        
        Validator::extend('articles_prohibited_characters', function ($attribute, $value, $parameters, $validator) {
            //I have added space to the array of allowed characters only for the purpose to show an error message
            //just once, so we don't have two messages for prohibited characters and for spaces detection.
            //We just need only one message telling that spaces are not allowed.
            $allowed_characters = array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", 
            "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", 
                "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ", "-", "_");
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
                $all_keywords = (new AlbumsRepository())->get_all_albums_keywords();
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
                $all_keywords = (new ArticlesRepository())->get_all_folders_keywords();
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
                $all_keywords = (new KeywordsRepository())->get_all_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('username_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old username (from parameters[0]) with a new username ($value) 
            //to avoid any misunderstanding when do username uniqueness check.
            //When we edit existing record we might change something without changing
            //a username. If we don't compare new username with its previous value, the system
            //might think username is not unique as user is trying to assign already existing username.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $all_names = (new AdminUsersRepository())->get_all_names();
                foreach ($all_names as $name) {
                    if ((strcmp(strtolower($name), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('email_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old email (from parameters[0]) with a new email ($value) 
            //to avoid any misunderstanding when do email uniqueness check.
            //When we edit existing record we might change something without changing
            //an email. If we don't compare new email with its previous value, the system
            //might think email is not unique as user is trying to assign already existing email.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $all_emails = (new AdminUsersRepository())->get_all_emails();
                foreach ($all_emails as $email) {
                    if ((strcmp(strtolower($email), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('picture_keyword_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $all_keywords = (new AdminPicturesRepository())->get_all_pictures_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
            return true;
            }
        });
        
        Validator::extend('article_keyword_uniqueness_check', function ($attribute, $value, $parameters, $validator) {
            //We need to compare an old keyword (from parameters[0]) with a new keyword ($value) 
            //to avoid any misunderstanding when do keyword uniqueness check.
            //When we edit existing record we might change something without changing
            //a keyword. If we don't compare new keyword with its previous value, the system
            //might think keyword is not unique as user is trying to assign already existing keyword.
            if ((strcmp($value, $parameters[0])) == 0) {
                return true;
            } else {
                $all_keywords = (new AdminArticleRepository())->get_all_articles_keywords();
                foreach ($all_keywords as $keyword) {
                    if ((strcmp(strtolower($keyword), strtolower($value))) == 0){
                        return false;
                    }
                }
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
