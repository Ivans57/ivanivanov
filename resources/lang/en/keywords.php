<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Keyword;

//Fetching all data from table:
$keywords = Keyword::all();
        
//Making our own array
        $en_keywords = array();
        
        //Filling our own array by the data from used table.
        foreach ($keywords as $keyword) {
            
        $en_keywords [$keyword->keyword] = $keyword->text;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_keywords;
