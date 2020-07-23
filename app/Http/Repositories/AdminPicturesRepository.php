<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use Carbon\Carbon;
use App\Picture;
use App\Http\Repositories\AlbumParentsRepository;

class AdminPicturesRepository {
    
    //Stores an album in database and saves its folder in a File System.
    public function store($request) {
        //$input = $request->all();
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
        
        //This function is returning a new file name, because we need to save this name in a database.
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
    
    //This function is required only to call function get_full_directory_path from AlbumParentsRepository.
    //It is needed to form a path for newly created folder for albums and pictures in a file system.
    public function getDirectoryPath($directory_id) {
        $to_get_full_directory_path = new AlbumParentsRepository();
        $full_path = $to_get_full_directory_path->get_full_directory_path($directory_id, "", "keyword");       
        return $full_path;
    }
}