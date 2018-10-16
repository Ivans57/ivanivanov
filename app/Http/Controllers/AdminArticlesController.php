<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;

//We don't need the line below. May be we will need it in a future.
//use Illuminate\Http\Request;

class AdminArticlesController extends Controller
{
    //
    protected $current_page;
    protected $navigation_bar_obj;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct(ArticlesRepository $articles){
        
        $this->folders = $articles;
        $this->current_page = 'Articles';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();      
    }
    
    //
    public function index(){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        //On the line below we are fetching all articles from the database
        $folders = $this->folders->getAllFolders(25);
        
        return view('adminpages.adminfolders')->with([
            'main_links' => $main_links,
            'headTitle' => $headTitle,
            'folders' => $folders
            ]);
    }
    
    public function create(){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        return view('adminpages.adminfolders')->with([
            'main_links' => $main_links,
            'headTitle' => $headTitle
            ]);
    }
}
