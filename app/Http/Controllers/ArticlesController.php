<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;

//use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    protected $folders;
    protected $current_page;
    protected $navigation_bar_obj;
    
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

    public function index(){  
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);     
        $headTitle= __('keywords.'.$this->current_page);
             
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        $folders = $this->folders->getAllFolders($items_amount_per_page);
        
        return view('pages.folders')->with([
            'headTitle' => $headTitle,
            'main_links' => $main_links,
            'folders' => $folders,
            'items_amount_per_page' => $items_amount_per_page
            ]);
    }
    
    public function showFolder($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        
        $folders_and_articles_full_info = $this->folders->getFolder($items_amount_per_page, $keyword, $page);
        
        return view('pages.folder')->with([
            'main_links' => $main_links,
            'headTitle' => $folders_and_articles_full_info->head_title,
            'folderName' => $folders_and_articles_full_info->folder_name,           
            'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
            'articleAmount' => $folders_and_articles_full_info->articleAmount,
            'folderParents' => $folders_and_articles_full_info->folderParents,
            'total_number_of_items' => $folders_and_articles_full_info->total_number_of_items,
            'number_of_pages' => $folders_and_articles_full_info->number_of_pages,
            'current_page' => $folders_and_articles_full_info->current_page,
            'previous_page' => $folders_and_articles_full_info->previous_page,
            'next_page' => $folders_and_articles_full_info->next_page,
            'items_amount_per_page' => $items_amount_per_page
            ]);

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
