<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use \App\Article;

//This can be used for localization implementation.
//I have shown this just for example. We don't need it.
//There is a different way to do localiztion as well.
//Please check bookmarks in a Google Chrome browser!
//use Illuminate\Support\Facades\Lang;

class HomeController extends Controller
{
     
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(){
        $this->current_page = 'Home';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    public function index(){
        
        //We need the line below to make main navigation links
        //We can't make global variable and initialize it in constructor because
        //localization won't be applied
        //Localiztion gets applied only if we call some certaion method from any controller
        //!Need to think is it possible still to apply localization in constructor!    
        $main_links = $this->navigation_bar_obj->get_main_website_links($this->current_page);
        
        //The code below is supposed to be in repository, but as there is only one method 
        //in this controller and just few lines of code, I left it in the controller.
        $home = Article::where('keyword', '=', $this->current_page)->first();
        
        //If there is no article with keyword "Home", then need to add one, no formatting is required,
        //because all styles for that article have been already written in the website css file.
        
        //The code below won't allow any error happen in case there is no article with keyword "Home". 
        if (is_null($home)) {
            $article_title = "";
            $article_body = "";
        } else {
            $article_title = $home->article_title;
            $article_body = $home->article_body;
        }  
        //We don't need it. This is just an example how we can pass one variable
        // to the view.
        //return view('pages.home')->with('headTitle', $headTitle);
        
        return view('pages.home')->with([
            'main_links' => $main_links,
            'headTitle' => __('keywords.'.$this->current_page),
            'article_title' => $article_title,
            'article_body' => $article_body
            ]);    
    }
    
}
