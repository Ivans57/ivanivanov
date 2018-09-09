<?php

namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\LangLink;


$langLinks = LangLink::all();
        
        $ru_langLinks = array();
        
        foreach ($langLinks as $langLink) {
            
        $ru_langLinks [$langLink->keyword] = $langLink->language;
        
        }
        
        return $ru_langLinks;


