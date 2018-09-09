<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Paginator;


//We need this "lang" approach to have an access to our names in views with just a key.
//May be we can simplify our project and get rid of all these "lang" if we use only variables.

$paginatorElements = Paginator::all();
        
        $en_paginatorElements = array();
        
        foreach ($paginatorElements as $paginatorElement) {
            
        $en_paginatorElements [$paginatorElement->keyword] = $paginatorElement->text;
        
        }
        
        return $en_paginatorElements;
