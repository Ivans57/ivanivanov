<?php

namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\LangLink;


$langLinks = LangLink::all();
        
        $en_langLinks = array();
        
        foreach ($langLinks as $langLink) {
            
        $en_langLinks [$langLink->keyword] = $langLink->language;
        
        }
        
        return $en_langLinks;
