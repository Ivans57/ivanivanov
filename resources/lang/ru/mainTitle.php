<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\MainTitle;


$mainTitles = MainTitle::all();
        
        $ru_mainTitles = array();
        
        foreach ($mainTitles as $mainTitle) {
            
        $ru_mainTitles [$mainTitle->keyword] = $mainTitle->title;
        
        }
        
        return $ru_mainTitles;


//Don't delete it! We will need it in a fufture for experiments!
/*
return [

    'PersonalWebPageOfIvanIvanov' => '<p>Творческая страничка</p><p style="margin-top: -22px; margin-left: 227px;">Ивана Иванова</p>',

];*/