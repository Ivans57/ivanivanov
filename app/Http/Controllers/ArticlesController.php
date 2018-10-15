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
        $headTitle= __('mainLinks.'.$this->current_page);
        
        //$folders = \App\Folder::where('included_in_folder_with_id', '=', NULL)->get();
        //$album_links = $this->albums->getAllAlbums();
        $folders = $this->folders->getAllFolders();
        
        return view('pages.folders')->with([
            'headTitle' => $headTitle,
            'main_links' => $main_links,
            'folders' => $folders,
            ]);
        //return $folders;
    }
    
    public function showFolder($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        $folders_and_articles_full_info = $this->folders->getFolder($keyword, $page);
        
        return view('pages.folder')->with([
            'main_links' => $main_links,
            'headTitle' => $folders_and_articles_full_info->head_title,
            'folderName' => $folders_and_articles_full_info->folder_name,           
            'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
            'articleAmount' => $folders_and_articles_full_info->articleAmount, 
            'folders_and_articles_total_number' => $folders_and_articles_full_info->folders_and_articles_total_number,
            'folders_and_articles_number_of_pages' => $folders_and_articles_full_info->folders_and_articles_number_of_pages,
            'folders_and_articles_current_page' => $folders_and_articles_full_info->folders_and_articles_current_page,
            'folders_and_articles_previous_page' => $folders_and_articles_full_info->folders_and_articles_previous_page,
            'folders_and_articles_next_page' => $folders_and_articles_full_info->folders_and_articles_next_page
            ]);

    }
    
    public function showArticle($keyword){
        
        $main_links = $this->navigation_bar_obj->get_main_links($this->current_page);
        
        $article = $this->folders->getArticle($keyword);
        
        //We will always take zero element from the array below, because we will always
        //have only one element in the array as the keyword is unique.
        
        //Here I unsuccesfully attempted to use html tags which I kept in text in database.
        //$article_body = htmlspecialchars_decode($article[0]->article_body, ENT_HTML5);
        $hello_world = 'Hello World!';
        
        return view('pages.article')->with([
            'main_links' => $main_links,
            'headTitle' => $article[0]->article_title,
            'article_description' => $article[0]->article_description,
            //'article_body' => $article_body,
            'article_body' => $article[0]->article_body,
            'article_author' => $article[0]->article_author,
            'article_source' => $article[0]->article_source,
            'created_at' => $article[0]->created_at,
            'hello_world' => $hello_world    
            ]);

    }
}
