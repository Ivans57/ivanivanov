<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\MainLink;


$mainLinks = MainLink::all();
        
        $ru_mainLinks = array();
        
        foreach ($mainLinks as $mainLink) {
            
        $ru_mainLinks [$mainLink->keyword] = $mainLink->link_name;
        
        }
        
        return $ru_mainLinks;
        
        
//Don't delete it! We will need it in a fufture for experiments!        
/*
return [

    'Home' => 'Начало',
    'AboutMe' => 'Обо мне',

];*/