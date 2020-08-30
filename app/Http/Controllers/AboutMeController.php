<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\AboutMeRepository;

//This can be used for localization implementation.
//I have shown this just for example. We don't need it.
//There is a different way to do localiztion as well. 
//Please check bookmarks in a GCh browser!
//use Illuminate\Support\Facades\Lang;


class AboutMeController extends Controller {   
    
    protected $current_page;
    protected $navigation_bar_obj;
    protected $about_me;


    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(AboutMeRepository $about_me){
        //The line below is making an object of required repository class
        $this->about_me = $about_me;
        $this->current_page = 'AboutMe';
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
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
         
        $article = (new AboutMeRepository())->getAboutMeArticle($this->current_page);
        
        return view('pages.aboutMe')->with([
            'main_links' => $main_links,
            'headTitle' => $article->articleTitle,
            'article_body' => $article->articleBody
            ]);
    }
    
}
