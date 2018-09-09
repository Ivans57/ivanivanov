<?php


namespace App\Http\Controllers;

use App\AlbumContent;

//Fetching all data from table:
$albumContents = AlbumContent::all();
        
//Making our own array
        $en_albumContents = array();
        
        //Filling our own array by the data from used table.
        foreach ($albumContents as $albumContent) {
            
        $en_albumContents [$albumContent->keyword] = $albumContent->text;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_albumContents;