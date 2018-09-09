<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Album;

//Need toperform a small research if we really need this. One more time find out about localiztion.
$albums = Album::all();
        
        $en_albums = array();
        
        foreach ($albums as $album) {
            
        $en_albums [$album->keyword] = $album->album_name;
        
        }
        
        return $en_albums;