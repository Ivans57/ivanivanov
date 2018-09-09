<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\MainLink;


//We need this "lang" approach to have an access to our names in views with just a key.
//May be we can simplify our project and get rid of all these "lang" if we use only variables.

$mainLinks = MainLink::all();
        
        $en_mainLinks = array();
        
        foreach ($mainLinks as $mainLink) {
            
        $en_mainLinks [$mainLink->keyword] = $mainLink->link_name;
        
        }
        
        return $en_mainLinks;

        
//Don't delete it! We will need it in a fufture for experiments!
/*
  return [

    'Home' => 'Home',
    'AboutMe' => 'About me',

];*/