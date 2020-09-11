<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Folder;
use App\Article;
use App\Http\Repositories\ArticlesRepository;

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
    
    public function destroy($entity_types_and_keywords) {
        
        $directories_and_files = $this->get_directories_and_files_from_string($entity_types_and_keywords);
        
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
    
    private function get_directories_and_files_from_string($entity_types_and_keywords) {
        //All keywords are coming as one string. They are separated by ";"
        $directories_and_files = explode(";", $entity_types_and_keywords);
        //The function below removes the last (empty) element of the array.
        array_pop($directories_and_files);
        
        $directories = array();
        $files = array();
        
        foreach ($directories_and_files as $directory_or_file) {
            //Apart of keywords string in incoming parameter has indicators,
            //their purpose is to identify does this keyword belongs to foldre or article.
            //Keyword is separated from its indicator by "+".
            $directory_or_file_array = explode("+", $directory_or_file);
            //Depends what is in array, it needs to go to its own array.
            //Folders and Articles should be separate.
            if ($directory_or_file_array[0] == "directory") {
                array_push($directories ,$directory_or_file_array[1]);
            } else {
                array_push($files ,$directory_or_file_array[1]);
            }
        }
        
        return $directories_and_files_array = [$directories, $files];
    }
}
