<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;
use App\Folder;
//The reuqtes below is required for search.
use Illuminate\Http\Request;
//We need the line below to peform some manipulations with strings
//e.g. making all string letters lowe case.
use Illuminate\Support\Str;

class ArticlesController extends Controller
{
    protected $folders;
    protected $current_page;
    protected $common;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    public function __construct(ArticlesRepository $articles) {

        $this->folders = $articles;
        $this->current_page = 'Articles';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->common = new CommonRepository();
        $this->is_admin_panel = false;
    }

    public function index($sorting_mode = null) {  
        
        $main_links = $this->common->get_main_website_links($this->current_page);
             
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        
        //In the next line the data are getting extracted from the database and sorted.
        //The fourth parameter is 'folders', because currently we are working with level 0 folders.
        $sorting_data = $this->common->sort_for_albums_or_articles(0, $items_amount_per_page, $sorting_mode, 1, 'folders');
        
        //Below we need to do the check if entered page number is more than
        //actual number of pages, we redirect the user to the last page.
        //To avoid indefinite looping need to check whether a section has at least one element.
        if ($sorting_data["directories_or_files"][0] && 
                ($sorting_data["directories_or_files"]->currentPage() > $sorting_data["directories_or_files"]->lastPage())) {
                    return $this->common->redirect_to_last_page_one_entity(Str::lower($this->current_page), 
                    $sorting_data["directories_or_files"]->lastPage(), $this->is_admin_panel);
        } else {
            return view('pages.folders_and_articles.folders')->with([
                'headTitle' => __('keywords.'.$this->current_page),
                'main_links' => $main_links,
                'folders' => $sorting_data["directories_or_files"],
                'section' => Str::lower($this->current_page),
                'sorting_mode' => ($sorting_mode) ? $sorting_mode : 'sort_by_creation_desc',
                'items_amount_per_page' => $items_amount_per_page,
                //The line below is required to show correctly display_invisible elements.
                'all_folders_count' => Folder::where('included_in_folder_with_id', '=', null)->count(),
                'what_to_search' => 'folders'
                ]);
        }
    }
    
    public function showFolder($keyword, $page, $sorting_mode = null, $folders_or_articles_first = null) {
        
        $main_links = $this->common->get_main_website_links($this->current_page);
        
        //We need the variable below to display how many items we need to show per one page
        $items_amount_per_page = 16;
        
        //We need to call the method below to clutter down current method in controller.
        return $this->folders->showFolderView(Str::lower($this->current_page), 
                    $page, $keyword, $items_amount_per_page, $main_links, $this->is_admin_panel, 0, $sorting_mode, $folders_or_articles_first);
    }
    
    public function showArticle($keyword) {
        
        $main_links = $this->common->get_main_website_links($this->current_page);
        
        $article = $this->folders->getArticle($keyword);
        
        return view('pages.folders_and_articles.article')->with([
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
    
    public function searchFolderOrArticle(Request $request) {
        //The variable below is being used in getFoldersOrArticlesFromSearch() and view() functions.
        $items_amount_per_page = 14;
        
        //The fourth parameter about visibility cannot be passed as it is, because when user is switching from normal mode to search mode previous visibility rule
        //should be discarded.
        $folders_or_articles_with_info = $this->folders->getFoldersOrArticlesFromSearch($request->input('find_by_name'), $request->input('page_number'), 
                                                                $items_amount_per_page, $request->input('what_to_search'), $request->input('search_is_on') == '0' ? 'all' : 
                                                                1/*$show_only_visible*/, $request->input('sorting_mode'));
        
        //Need to check if all variables below are being used!
        $folders_or_articles = $folders_or_articles_with_info->items_on_page;
        $all_items_amount = $folders_or_articles_with_info->all_items_count;
        //The variable below is required to display bisibility checkbox properly.
        $pagination_info = $folders_or_articles_with_info->paginator_info;
        //The variable below is required for sort to indicate which function to call index or search.
        $search_is_on = "1";
        $sorting_method_and_mode = ($request->input('sorting_mode') === null) ? "0" : $request->input('sorting_mode');
        $section = "articles";
        $what_to_search = $request->input('what_to_search');
        
        $path = "";       
        $title = view('pages.folders_and_articles.folder_search_title')->render();       
        
        $content = view('pages.folders_and_articles.folders_searchcontent', 
                compact("folders_or_articles", "all_items_amount", "items_amount_per_page", "pagination_info", "search_is_on", "sorting_method_and_mode", 
                        "section", "what_to_search"))->render();
        
        
        return response()->json(compact('path', 'title', 'content'));
    }
}
