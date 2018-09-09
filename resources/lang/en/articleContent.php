<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\ArticleContent;

//Fetching all data from table:
$articleContents = ArticleContent::all();
        
//Making our own array
        $en_articleContents = array();
        
        //Filling our own array by the data from used table.
        foreach ($articleContents as $articleContent) {
            
        $en_articleContents [$articleContent->keyword] = $articleContent->content;
        
        }
        
        //Returning an array with a necessary data from our table. 
        //So in output we will have the same result as we would have 
        //using a code in a bottom of this page.
        return $en_articleContents;