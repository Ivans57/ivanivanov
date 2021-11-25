<?php

namespace App\Http\Repositories;

//We need AlbumsRepository to provide Album Keyword's uniqueness check.
use App\Http\Repositories\AlbumsRepository;
//We need ArticlesRepository to provide Article Keyword's uniqueness check.
use App\Http\Repositories\ArticlesRepository;
//We need ArticlesRepository to provide Keyword's uniqueness check.
use App\Http\Repositories\KeywordsRepository;
//We need AlbumsRepository to provide Picture Keyword's uniqueness check.
use App\Http\Repositories\AdminPicturesRepository;
//We need AdminArticleRepository to provide Articles Keyword's uniqueness check.
use App\Http\Repositories\AdminArticleRepository;
//We need AdminUsersRepository to provide Username and Email uniqueness check.
use App\Http\Repositories\AdminUsersRepository;


class ValidationRepository {
    
    //This function is required for prohibited characters check in custom validation.
    public function get_allowed_characters($kind_of_check) {
        switch ($kind_of_check) {
            case "keyword":
                //I have added space to the array of allowed characters only for the purpose to show an error message
                //just once, so we don't have two messages for prohibited characters and for spaces detection.
                //We just need only one message telling that spaces are not allowed.
                return array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", 
                             "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", 
                             "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z", " ");
            case "directory_and_picture":
                return array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", 
                             "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", 
                             "W", "w", "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
            case "article":
                return array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", 
                             "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", 
                             "W", "w", "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ", "-", "_");
            case "username":
                return array("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", 
                             "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", 
                             "W", "w", "X", "x", "Y", "y", "Z", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "_");
            default:
                return array();
        }
    }
    
    //This function is required for uniqueness check in custom validation.
    public function uniqueness_check_for_validation($kind_of_check, $value) {
        $all_items = $this->fetch_items_for_uniqueness_check($kind_of_check);
        
        foreach ($all_items as $item_to_compare_to) {
                if ((strcmp(strtolower($item_to_compare_to), strtolower($value))) == 0){
                        return false;
                }
            }
        return true;
    }
    
    //This function is required for uniqueness check in custom validation.
    private function fetch_items_for_uniqueness_check($kind_of_check) {
        switch ($kind_of_check) {
            case "username":
                return (new AdminUsersRepository())->get_all_names_or_emails($kind_of_check);
            case "email":
                return (new AdminUsersRepository())->get_all_names_or_emails($kind_of_check);
            case "keyword":
                return (new KeywordsRepository())->get_all_keywords();
            case "album_keyword":
                return (new AlbumsRepository())->get_all_albums_keywords();
            case "picture_keyword":
                return (new AdminPicturesRepository())->get_all_pictures_keywords();
            case "folder_keyword":
                return (new ArticlesRepository())->get_all_folders_keywords();
            case "article_keyword":
                return (new AdminArticleRepository())->get_all_articles_keywords();
            default:
                return array();
        }
    }
}
