<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\MainTitle;


$mainTitles = MainTitle::all();
        
        $en_mainTitles = array();
        
        foreach ($mainTitles as $mainTitle) {
            
        $en_mainTitles [$mainTitle->keyword] = $mainTitle->title;
        
        }
        
        return $en_mainTitles;

        
//Don't delete it! We will need it in a fufture for experiments!
/*
return [

    'PersonalWebPageOfIvanIvanov' => '<p>Personal Web Page of </p><p style="margin-top: -22px; margin-left: 190px;">Ivan Ivanov</p>',

];*/