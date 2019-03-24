<?php

//!!!This is a controller we need for experiment purposes. 
//We don't need it in our final project.!!!

namespace App\Http\Controllers;

//We don't need it now. But we may need it in a future, 
//when we make a request to database from this controller.
//use Illuminate\Http\Request;

//Two lines below are showing how to use a model with a controller.
//use App\Article;
//use App\MainLink;

//We need this class to check how to use it, it's constructor and 
//how to call it's methods.

/*
class ForTest {
        
        private $check;
        
        public function __construct() {
            
            $this->check = 1;
        }
        
        
        public function getCheck(){
            
            //$this->check = 1;
            
            return $this->check;
            
            //return 1;
        }
    }*/

class TestController extends Controller
{
    
    public function index(){
        
        //Example how to make an array from database table elements
        
        /*
        $mainlinks = MainLink::all();
        
        $test = array();
        
        foreach ($mainlinks as $mainlink) {
            
            //this option will always renew the same element of array but won't
            //add a new one
            //$test = [$mainlink->keyword => $mainlink->name];
            //$test = $test;
            
            //We need this option. It will add a new alement to an array.
              $test [$mainlink->keyword] = $mainlink->name;
        }
        
        return $test;*/
        
        
        //We need this code to check how to use class, it's constructor and 
        //how to call it's methods.
        
        /*
        $proverka = new ForTest();
        
        $vivod = $proverka->getCheck();
        
        return $vivod;*/
        
        //Testing bootsrap on a test view.
        //return phpinfo();
        /*$for_test = 1;
        
        $one_more_for_test = 2;
        
        $test_for_return = $for_test + $one_more_for_test;

        return $test_for_return;*/
        return "Hello World!";
               
    }
}
