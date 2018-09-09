<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Folder;

//Need toperform a small research if we really need this. One more time find out about localiztion.
$folders = Folder::all();
        
        $en_folders = array();
        
        foreach ($folders as $folder) {
            
        $en_folders [$folder->keyword] = $folder->folder_name;
        
        }
        
        return $en_folders;