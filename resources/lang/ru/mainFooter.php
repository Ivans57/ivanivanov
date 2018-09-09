<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\MainFooter;


$mainFooters = MainFooter::all();
        
        $ru_mainFooters = array();
        
        foreach ($mainFooters as $mainFooter) {
            
        $ru_mainFooters [$mainFooter->keyword] = $mainFooter->main_footer_title;
        
        }
        
        return $ru_mainFooters;
        
//Некоммерческий проект Ивана Иванова