<?php

namespace App\Http\Repositories;

//We need the line below to use localization. 
use App;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Album;
use App\Picture;
use App\Http\Repositories\AlbumParentsRepository;
use App\Http\Repositories\AlbumsRepository;


class AdminAlbumsRepository extends AlbumsRepository {
    
    //Stores an album in the database and saves its folder in the File System.
    public function store($request) {
        $input = $request->all();      
        $this->create_database_record($input);       
        $this->save_directory_in_file_system($input);
    }
    
    private function create_database_record($input) {
        //We need to do the following if case because,
        //if user doesn't choose any parent album
        //then parent album id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record
        if ($input['included_in_album_with_id'] == 0) {
            $input['included_in_album_with_id'] = NULL;
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
        Album::create($input);
    }
    
    private function save_directory_in_file_system($input) {
        if (App::isLocale('en')) {
            $root_path = 'albums/en';
        } else {
            $root_path = 'albums/ru';
        }      
        if ($input['included_in_album_with_id']) {
            $full_path = $this->getDirectoryPath($input['included_in_album_with_id']);
            $full_path = $root_path.$full_path."/";
        } else {
            $full_path = $root_path."/";
        }        
        Storage::disk('public')->makeDirectory($full_path.$input['keyword'], 0777, true);
    }
    
    //Updates an album in database and updates its folder in the File System.
    public function update($keyword, $request) {
        $edited_album = Album::where('keyword', '=', $keyword)->firstOrFail();       
        $input = $request->all();
        
        //Moving and renaming a folder in a file system.
        $this->update_directory_in_file_system($input, $edited_album);
        
        //Moving and renaming an album in a database.
        $this->update_database_record($input, $edited_album);       
    }
    
    private function update_directory_in_file_system($input, $edited_album) {      
        if (App::isLocale('en')) {
            $root_path = storage_path('app/public/albums/en');
        } else {
            $root_path = storage_path('app/public/albums/ru');
        }     
        if ($edited_album->included_in_album_with_id) {
            $path_before = $this->getDirectoryPath($edited_album->included_in_album_with_id);
            $path_before = $root_path.$path_before."/";
        } else {
            $path_before = $root_path."/";
        }      
        if ($input['included_in_album_with_id']) {
            $path_after = $this->getDirectoryPath($input['included_in_album_with_id']);
            $path_after = $root_path.$path_after."/";
        } else {
            $path_after = $root_path."/";
        }        
        rename($path_before.$input['old_keyword'], $path_after.$input['keyword']);
    }
    
    private function update_database_record($input, $edited_album) {
        //We need to do the following if case because,
        //if user doesn't choose any parent album
        //then parent album id will be assigned 0 instead of NULL
        //which will cause an error whilst saving a new record.
        if ($input['included_in_album_with_id'] == 0){
            $input['included_in_album_with_id'] = NULL;
        }       
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }       
        $input['updated_at'] = Carbon::now();       
        $edited_album->update($input);
        //Album::update($input);
    }
    
    //There might be three types of views for return depends what user needs to delete,
    //album(s), picture(s), both albums and pictures.
    public function return_delete_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page) {
        //This case is for albums.
        if (sizeof($direcotries_and_files_array[0]) > 0 && sizeof($direcotries_and_files_array[1]) == 0) {
            return $this->return_delete_album_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page);
            //This case is for pictures.    
        } else if (sizeof($direcotries_and_files_array[1]) > 0 && sizeof($direcotries_and_files_array[0]) == 0) {
            return $this->return_delete_picture_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page);
        //This case is for both albums and pictures.    
        } else if (sizeof($direcotries_and_files_array[0]) > 0 && sizeof($direcotries_and_files_array[1]) > 0) {
            return $this->return_delete_album_and_picture_view($entity_types_and_keywords, $current_page);
        }
    }
    
    //This delete view is for folders.
    private function return_delete_album_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page) {
        return view('adminpages.directory.delete_directory')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'albums',
            'plural_or_singular' => (sizeof($direcotries_and_files_array[0]) > 1) ? 'plural' : 'singular'   
            ]);
    }
    
    //This delete view is for articles.
    private function return_delete_picture_view($direcotries_and_files_array, $entity_types_and_keywords, $current_page) {
        return view('adminpages.pictures.delete_picture')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'albums',
            'plural_or_singular' => (sizeof($direcotries_and_files_array[1]) > 1) ? 'plural' : 'singular'   
            ]);
    }
    
    //This delete view is for both folders and articles.
    private function return_delete_album_and_picture_view($entity_types_and_keywords, $current_page) {
        return view('adminpages.directory.delete_directories_and_files')->with([
            //Actually we do not need any head title as it is just a partial view.
            //We need it only to make the variable initialized. Othervise there will be an error.
            'headTitle' => __('keywords.'.$current_page),
            'entity_types_and_keywords' => $entity_types_and_keywords,
            //The line below is required for form path.
            'section' => 'albums' 
            ]);
    }
    
    public function destroy($entity_types_and_keywords) {       
        $directories_and_files = (new CommonRepository())->get_directories_and_files_from_string($entity_types_and_keywords);
        
        //The first element is directories array, the second element is files array.
        if (sizeof($directories_and_files[0]) > 0) {
            foreach ($directories_and_files[0] as $keyword) {
                $this->destroy_album($keyword);
            }
        }      
        if (sizeof($directories_and_files[1]) > 0) {
            foreach ($directories_and_files[1] as $keyword) {
                $this->destroy_picture($keyword);
            }
        }
    }
    
    private function destroy_album($keyword) {
        $album_to_remove = Album::select('id')->where('keyword', '=', $keyword)->firstOrFail();
        
        $path = $this->getDirectoryPath($album_to_remove->id);
        $full_path = storage_path('app/public/'.((App::isLocale('en')) ? 'albums/en' : 'albums/ru').$path);
        //Removes from File System.
        $this->deleteDirectory($full_path);
        
        //Removes from Database.
        $album_to_remove->delete();
    }
    
    //Removes picture's record from the database and deletes its file from the file system.
    private function destroy_picture($keyword) {
        $picture_to_remove = Picture::select('id', 'file_name', 'included_in_album_with_id')->where('keyword', '=', $keyword)->firstOrFail();

        //Removes from File System.
        unlink(storage_path('app/public/'.((App::isLocale('en')) ? 'albums/en' : 'albums/ru').
                                $this->getDirectoryPath($picture_to_remove->included_in_album_with_id).'/').$picture_to_remove->file_name);     
        //Removes from Database.
        $picture_to_remove->delete();
    }
    
    //This function is required only to call function get_full_directory_path from AlbumParentsRepository.
    //It is needed to form a path for newly created folder for albums and pictures in a file system.
    public function getDirectoryPath($directory_id) {
        $full_path = (new AlbumParentsRepository())->get_full_directory_path($directory_id, "", "keyword");       
        return $full_path;
    }
    
    //As the basic php function cannot delete not empty folder and Laravel functions are not working,
    //we will make our own function, based on basic php functions.
    private function deleteDirectory($full_path) {
        $contents = scandir($full_path);
        
        if (count($contents) < 3) {
            rmdir($full_path);
        } else {
            //Need to remove first to elements of an array, 
            //because scandir function includes in a directory's contents signs "." and "..".
            unset($contents[0]);
            unset($contents[1]);
            foreach ($contents as $content) {
                //First of all need to delete all contents.
                $this->deleteFileOrDirectory($full_path."/".$content);               
            }
            //Then can remove a parent directory.
            rmdir($full_path);
        }       
    }
    
    //The function below is needed to simplify deleteDirectory function.
    private function deleteFileOrDirectory($current_item) {
        if (is_file($current_item) === true) {
            unlink($current_item); 
        } else if (is_dir($current_item) === true) {
            $this->deleteDirectory($current_item);
        }
    }
}