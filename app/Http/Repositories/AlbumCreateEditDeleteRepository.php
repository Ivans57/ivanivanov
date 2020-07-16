<?php

namespace App\Http\Repositories;

use App\Http\Repositories\AlbumParentsRepository;


class AlbumCreateEditDeleteRepository {
    //This function is needed only to call private function get_full_directory_path.
    //It is needed to form a path for newly created folder for albums and pictures in a file system.
    public function getDirectoryPath($directory_id) {
        $to_get_full_directory_path = new AlbumParentsRepository();
        $full_path = $to_get_full_directory_path->get_full_directory_path($directory_id, "", "keyword");       
        return $full_path;
    }
    
    //As the basic php function cannot delete not empty folder and Laravel functions are not working,
    //we will make our own function, based on basic php functions.
    public function deleteDirectory($full_path) {
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