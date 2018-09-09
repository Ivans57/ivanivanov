<?php


namespace App\Http\Controllers;

use App\FolderContent;

//Fetching all data from table:
$folderContents = FolderContent::all();
        
//Making our own array
        $en_folderContents = array();
        
        //Filling our own array by the data from used table.
        foreach ($folderContents as $folderContent) {
            
        $en_folderContents [$folderContent->keyword] = $folderContent->text;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_folderContents;