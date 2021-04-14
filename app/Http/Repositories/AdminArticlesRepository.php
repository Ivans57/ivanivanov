<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Folder;
use App\Article;
use App\Http\Repositories\ArticlesRepository;
use App\Http\Repositories\CommonRepository;

class AdminArticlesRepository extends ArticlesRepository {
    
    public function store($request) {
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent folder
        //then parent folder id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record.
        if ($input['included_in_folder_with_id'] == 0){
            $input['included_in_folder_with_id'] = NULL;
        }       
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }      
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        Folder::create($input);
    }
    
    public function update($keyword, $request) {
        $edited_folder = Folder::where('keyword', '=', $keyword)->firstOrFail();       
        $input = $request->all();
        //We need to do the following if case because,
        //if user doesn't choose any parent folder
        //then parent folder id will be assigned 0 instead of NULL
        //which will cause an error when saving a new record.
        if ($input['included_in_folder_with_id'] == 0){
            $input['included_in_folder_with_id'] = NULL;
        }      
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }     
        $input['updated_at'] = Carbon::now();        
        $edited_folder->update($input);
    }
    
    //There might be three types of views for return depends what user needs to delete,
    //folder(s), article(s), both folders and articles.
    public function return_delete_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on) {
        //This case is for folders.
        if (sizeof($direcotries_and_files_array[0]) > 0 && sizeof($direcotries_and_files_array[1]) == 0) {
            return $this->return_delete_folder_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on);
            //This case is for articles.    
        } else if (sizeof($direcotries_and_files_array[1]) > 0 && sizeof($direcotries_and_files_array[0]) == 0) {
            return $this->return_delete_article_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on);
        //This case is for both folders and articles.    
        } else if (sizeof($direcotries_and_files_array[0]) > 0 && sizeof($direcotries_and_files_array[1]) > 0) {
            return $this->return_delete_folder_and_article_view($entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on);
        }
    }
    
    //This delete view is for folders.
    private function return_delete_folder_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on) {
        return view('adminpages.directory.delete_directory')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'articles',
            'plural_or_singular' => (sizeof($direcotries_and_files_array[0]) > 1) ? 'plural' : 'singular',
            //parent_keyword variable is required only to update page correctly after deleting elements.
            'parent_keyword' => $parent_keyword,
            //The variable below is required to redirect user to a proper page after delete, which depends on whether user is using search mode or not.
            'search_is_on' => $search_is_on
            ]);
    }
    
    //This delete view is for articles.
    private function return_delete_article_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on) {
        return view('adminpages.articles.delete_article')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'articles',
            'plural_or_singular' => (sizeof($direcotries_and_files_array[1]) > 1) ? 'plural' : 'singular',
            //parent_keyword variable is required only to update page correctly after deleting elements.
            'parent_keyword' => $parent_keyword,
            //The variable below is required to redirect user to a proper page after delete, which depends on whether user is using search mode or not.
            'search_is_on' => $search_is_on
            ]);
    }
    
    //This delete view is for both folders and articles.
    private function return_delete_folder_and_article_view($entity_types_and_keywords, $current_page, $parent_keyword, $search_is_on) {
        return view('adminpages.directory.delete_directories_and_files')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'articles',
            //parent_keyword variable is required only to update page correctly after deleting elements.
            'parent_keyword' => $parent_keyword,
            //The variable below is required to redirect user to a proper page after delete, which depends on whether user is using search mode or not.
            'search_is_on' => $search_is_on
            ]);
    }
    
    public function destroy($entity_types_and_keywords) {        
        $directories_and_files = (new CommonRepository())->get_directories_and_files_from_string($entity_types_and_keywords);
        
        //The first element is directories array, the second element is files array.
        if (sizeof($directories_and_files[0]) > 0) {
            foreach ($directories_and_files[0] as $keyword) {
                Folder::where('keyword', '=', $keyword)->delete();
            }
        }      
        if (sizeof($directories_and_files[1]) > 0) {
            foreach ($directories_and_files[1] as $keyword) {
                Article::where('keyword', '=', $keyword)->delete();
            }
        }
    }
    
}
