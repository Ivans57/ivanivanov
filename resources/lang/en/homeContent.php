<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\HomeContent;

//Fetching all data from table:
$homeContents = HomeContent::all();
        
//Making our own array
        $en_homeContents = array();
        
        //Filling our own array by the data from used table.
        foreach ($homeContents as $homeContent) {
            
        $en_homeContents [$homeContent->keyword] = $homeContent->content;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_homeContents;


//If we did not use a database, it would be enough just to use an array below.
//But as we use a database we need our code above.
//Don't delete it! We will need it in a fufture for experiments!
/*
return [

    'HomeText' => '<p><span>Dear</span> visitor,</p><p>Welcome to my personal web page. This is my first experimental web site I have made to improve my programming skills. I hope you will like it.</p>',

];*/