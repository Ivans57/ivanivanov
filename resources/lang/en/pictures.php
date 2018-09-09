<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Picture;

//Need toperform a small research if we really need this. One more time find out about localiztion.
$pictures = Picture::all();
        
        $en_pictures = array();
        
        foreach ($pictures as $picture) {
            
        $en_pictures [$picture->keyword] = $picture->picture_caption;
        
        }
        
        return $en_pictures;

