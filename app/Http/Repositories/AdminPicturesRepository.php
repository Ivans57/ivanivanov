<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use Carbon\Carbon;
use App\Picture;
use App\Http\Repositories\AlbumParentsRepository;

class AdminPicturesRepository {
    
    //Stores an album in the database and saves its folder in the File System.
    public function store($request) {
        $new_name = $this->save_picture_in_file_system($request);
        $this->create_database_record($request, $new_name);       
    }
    
    private function save_picture_in_file_system($request) {
        if (App::isLocale('en')) {
            $root_path = storage_path('app/public/albums/en');
        } else {
            $root_path = storage_path('app/public/albums/ru');
        }      
        $image = $request->file('image_select');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        
        //We don't need to perform any checks for included_in_album_with_id, 
        //because, as we cannot add any picture out of any album, 
        //this field will always have some normal value.
        $full_path = $this->getDirectoryPath($request->input('included_in_album_with_id'));
        $full_path = $root_path.$full_path;      
        $image->move($full_path, $new_name);
        
        //This function is returning a new file name, because we need to save this name in the database.
        return $new_name;
    }
    
    private function create_database_record($request, $new_name) {
        $input = $request->all();
            
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }       
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $input['file_name'] = $new_name;
        Picture::create($input);
    }
    
    //Updates a picture in the database and updates its file location in the File System.
    public function update($keyword, $request) {
        $edited_picture = Picture::where('keyword', '=', $keyword)->firstOrFail();       
        $input = $request->all();
        
        //Moving a picture in a file system.
        $this->move_picture_in_file_system($input, $edited_picture);
        
        //Changing picture record in a database.
        $this->update_database_record($input, $edited_picture);       
    }
    
    private function move_picture_in_file_system($input, $edited_picture) {
        $root_path = (App::isLocale('en')) ? storage_path('app/public/albums/en') : storage_path('app/public/albums/ru'); 
        
        $full_name_before = $root_path.$this->getDirectoryPath($edited_picture->included_in_album_with_id)."/".$edited_picture->file_name;     
        $full_name_after = $root_path.$this->getDirectoryPath($input['included_in_album_with_id'])."/".$edited_picture->file_name;
       
        rename($full_name_before, $full_name_after);
    }
    
    private function update_database_record($input, $edited_picture) {     
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }       
        $input['updated_at'] = Carbon::now();       
        $edited_picture->update($input);
    }
    
    //This function is required only to call function get_full_directory_path from AlbumParentsRepository.
    //It is needed to form a path for newly created folder for albums and pictures in a file system.
    public function getDirectoryPath($directory_id) {
        $full_path = (new AlbumParentsRepository())->get_full_directory_path($directory_id, "", "keyword");       
        return $full_path;
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //picture keyword or editing existing.
    public function get_all_pictures_keywords() {
        
        $all_pictures_keywords = \App\Picture::all('keyword');       
        $pictures_keywords_array = array();
        
        foreach ($all_pictures_keywords as $picture_keyword) {
            array_push($pictures_keywords_array, $picture_keyword->keyword);
        }    
        return $pictures_keywords_array;   
    }
}