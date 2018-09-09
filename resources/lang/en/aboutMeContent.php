<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\AboutMeContent;

//Fetching all data from table:
$aboutMeContents = AboutMeContent::all();
        
//Making our own array
        $en_aboutMeContents = array();
        
        //Filling our own array by the data from used table.
        foreach ($aboutMeContents as $aboutMeContent) {
            
        $en_aboutMeContents [$aboutMeContent->keyword] = $aboutMeContent->content;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_aboutMeContents;


//If we did not use a database, it would be enough just to use an array below.
//But as we use a database we need our code above.        
/*
return [

    'AboutMeText' => '<p><span>My</span> name is Ivan Ivanov. I am 32 years old. I live in London. I am an enthusiastic professional male with a good experience of working in different fields (power and electrical engineering, customer service, software developing, computer maintenance). My strengths are focusing on results, responsibility, team work, analytical mind,excellent customer service and ability to learn quickly. I’m interested in technologies, science and computers. I enjoy traveling, learning foreign languages and communicating with different people. I am looking forward to working in an innovative and technology-driven environment.</p>',

];*/