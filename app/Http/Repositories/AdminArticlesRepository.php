<?php

namespace App\Http\Repositories;

use Carbon\Carbon;
use App\Folder;
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
}
