<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;
//use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;

class ArticlesController extends Controller
{
    protected $folders;
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    public function __construct(ArticlesRepository $articles){

        $this->folders = $articles;
        $this->current_page = 'Articles';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();
        $this->is_admin_panel = false;
    }

    public function index(){  
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);     
        $headTitle= __('keywords.'.$this->current_page);
             
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        $folders = $this->folders->getAllFolders($items_amount_per_page, 0);
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page
        if ($folders->currentPage() > $folders->lastPage()) {
            return $this->navigation_bar_obj->redirect_to_last_page_one_entity(Str::lower($this->current_page), $folders->lastPage(), $this->is_admin_panel);
        } else {
            return view('pages.folders')->with([
                'headTitle' => $headTitle,
                'main_links' => $main_links,
                'folders' => $folders,
                'items_amount_per_page' => $items_amount_per_page
                ]);
        }
    }
    
    public function showFolder($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        
        //We need to call the method below to clutter down current method in controller
        return $this->folders->showFolderView(Str::lower($this->current_page), $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel, 0);
    }
    
    public function showArticle($keyword){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        $article = $this->folders->getArticle($keyword);
        
        //We will always take zero element from the array below, because we will always
        //have only one element in the array as the keyword is unique.
        
        //Here I unsuccesfully attempted to use html tags which I kept in text in database.
        //$article_body = htmlspecialchars_decode($article[0]->article_body, ENT_HTML5);
        
        return view('pages.article')->with([
            'main_links' => $main_links,
            'headTitle' => $article->article->article_title,
            'article_description' => $article->article->article_description,
            'article_body' => $article->article->article_body,
            'article_author' => $article->article->article_author,
            'article_source' => $article->article->article_source,
            'created_at' => $article->article->created_at,
            'articleParents' => $article->articleParents
            ]);
    }
}
