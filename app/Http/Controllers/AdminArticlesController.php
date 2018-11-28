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
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        //On the line below we are fetching all articles from the database
        $folders = $this->folders->getAllFolders($items_amount_per_page);
        
        return view('adminpages.adminfolders')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $headTitle,
            'folders' => $folders,
            'items_amount_per_page' => $items_amount_per_page
            ]);
    }
    
    public function showFolder($keyword, $page){
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 14;
        
        $folders_and_articles_full_info = $this->folders->getFolder($items_amount_per_page, $keyword, $page);
        
        return view('adminpages.adminfolder')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => $folders_and_articles_full_info->head_title,
            'folderName' => $folders_and_articles_full_info->folder_name,           
            'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
            'articleAmount' => $folders_and_articles_full_info->articleAmount,
            'folderParents' => $folders_and_articles_full_info->folderParents,
            'folders_and_articles_total_number' => $folders_and_articles_full_info->folders_and_articles_total_number,
            'folders_and_articles_number_of_pages' => $folders_and_articles_full_info->folders_and_articles_number_of_pages,
            'folders_and_articles_current_page' => $folders_and_articles_full_info->folders_and_articles_current_page,
            'folders_and_articles_previous_page' => $folders_and_articles_full_info->folders_and_articles_previous_page,
            'folders_and_articles_next_page' => $folders_and_articles_full_info->folders_and_articles_next_page,
            'items_amount_per_page' => $items_amount_per_page
            ]);
    }
    
    public function create(){
        
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);
        $headTitle= __('mainLinks.'.$this->current_page);
        
        return view('adminpages.adminfolders')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkStatus' => $main_links->keywordLinkIsActive,
            'headTitle' => $headTitle
            ]);
    }
}
