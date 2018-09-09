<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\HomeContent;


$homeContents = HomeContent::all();
        
        $ru_homeContents = array();
        
        foreach ($homeContents as $homeContent) {
            
        $ru_homeContents [$homeContent->keyword] = $homeContent->content;
        
        }
        
        return $ru_homeContents;

        
//If we did not use a database, it would be enough just to use an array below.
//But as we use a database we need our code above. 
//Don't delete it! We will need it in a fufture for experiments!
/*
return [

    'HomeText' => '<p><span>Уважаемый</span> посетитель,</p><p>Добро пожаловать на мою творческую страничку! Это мой первый экспериментальный веб сайт, который я создал с целью улучшения и развития своих навыков в области программирования. Очень надеюсь, что он вам понравится!</p>',

];*/