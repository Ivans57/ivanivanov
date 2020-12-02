<?php

namespace App\Http\Controllers;

//We need the line below to use localization. 
use App;
use Carbon\Carbon;
use App\Http\Requests\CreateEditArticleRequest;
use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;
use App\Folder;
use App\Article;

class AdminArticleController extends Controller
{
    protected $current_page;
    protected $navigation_bar_obj;
    //We need this variable to identify whether we are using a normal site
    //option or admin panel, as we have common repositories for the normal 
    //site and admin panel.
    protected $is_admin_panel;
    
    //There are some methods and variables which we will always use, so it will be better
    //if we call the and initialize in constructor
    public function __construct() {
        $this->current_page = 'Articles';
        //The line below is making an object of repository which contains
        //a method for making navigation bar main links
        //We can't get all these links in constructor as localiztion is applied 
        //only when we call some certain method in a route. We need to call the
        //method for main links using made main links object in controller's methods.
        $this->navigation_bar_obj = new CommonRepository();
        $this->is_admin_panel = true;
    }
    
    public function create($parent_keyword, $show_invisible, $sorting_mode = null, $folders_or_articles_first = null) {
        //Three the last parameters are required to keep previous sorting options.
        //Without them after creation of a new article, previous sorting options will be lost.
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page);    
        
        if ($parent_keyword != "0") {
            $parent_info = Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }       
        $parents = (new ArticlesRepository())->getArticlesParentsForPath($parent_info->id);
        
        return view('adminpages.articles.create_and_edit_article')->with([
            //Below main website links.
            'main_ws_links' => $main_links->mainWSLinks,
            //Below main admin panel links.
            'main_ap_links' => $main_links->mainAPLinks,
            'headTitle' => __('keywords.NewArticle'),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            //The variable below is required when user needs to cancel creating or editing 
            //an article and wants to return to a previous page.
            'parent_keyword' => $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            'parents' => $parents,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'create',
            //The line below is required for parent search and select and is being used in javascript.
            'section' => 'articles',
            //The variable below is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it will work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'file',
            //Three variables for sorting.
            'show_invisible' => $show_invisible,
            'sorting_mode' => $sorting_mode,
            'folders_or_articles_first' => $folders_or_articles_first
            ]);
    }
    
    public function store(CreateEditArticleRequest $request) {         
        $input = $request->all();
        
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }       
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        //As there is a field "article_keyword" instead of field "keyword" on a form,
        //need to assign to the field "keyword" field's "article_keyword" value.
        $input['keyword'] = $input['article_keyword'];
        Article::create($input);
        
        //The line below is required, because a user will nedd to return to a previous page
        //after has done with an article.
        $parent_keyword = Folder::select('keyword')
                    ->where('id', $input['folder_id'])->firstOrFail();
        
        return App::isLocale('en') ? redirect('/admin/articles/'.$parent_keyword->keyword.'/page/1/'.
                                     $input['sorting_show_invisible']."/".$input['sorting_sorting_mode']."/".
                                     $input['sorting_folders_or_articles_first']) : 
                                     redirect('/ru/admin/articles/'.$parent_keyword->keyword.'/page/1'.
                                     $input['sorting_show_invisible']."/".$input['sorting_sorting_mode']."/".
                                     $input['sorting_folders_or_articles_first']);
    }
    
    public function edit($parent_keyword, $keyword, $show_invisible, $sorting_mode = null, $folders_or_articles_first = null) {
        $main_links = $this->navigation_bar_obj->get_main_links_for_admin_panel_and_website($this->current_page); 
        
        $edited_article = Article::where('keyword', '=', $keyword)->firstOrFail();
        //As there is a field "article_keyword" instead of field "keyword" on a form,
        //need to assign to the field "article_keyword" field's "keyword" value.
        $edited_article['article_keyword'] = $edited_article['keyword'];
        
        if ($parent_keyword != "0") {
            $parent_info = Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }       
        return view('adminpages.articles.create_and_edit_article')->with([
            //Below main website links.
            'main_ws_links' => $main_links->mainWSLinks,
            //Below main admin panel links.
            'main_ap_links' => $main_links->mainAPLinks,
            'headTitle' => $edited_article->article_title,
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_keyword' => $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            'parents' => (new ArticlesRepository())->getArticlesParentsForPath($parent_info->id),
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'edit',
            'edited_article' => $edited_article,
            //The line below is required for form path.
            'section' => 'articles',
            //The variable below is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it will work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'file',
            //Three variables for sorting.
            'show_invisible' => $show_invisible,
            'sorting_mode' => $sorting_mode,
            'folders_or_articles_first' => $folders_or_articles_first
            ]);
    }
    
    public function update($keyword, CreateEditArticleRequest $request) {
        $edited_article = Article::where('keyword', '=', $keyword)->firstOrFail();
        $input = $request->all();
        
        //We need the if below, because form's tickbox is not null only
        //when it is ticked, otherwise it is null and the data from is_visible 
        //field will be lost. In the database is_visible is not nullable field,
        //and it keeps a boolean value.
        if (isset($input['is_visible'])== NULL) {
            $input['is_visible'] = 0;
        }            
        $input['updated_at'] = Carbon::now();     
        //As there is a field "article_keyword" instead of field "keyword" on a form,
        //need to assign to the field "keyword" field's "article_keyword" value.
        $input['keyword'] = $input['article_keyword'];
        
        $edited_article->update($input);
        
        //The line below is required, because a user will nedd to return to a previous page
        //after has done with an article.
        $parent_keyword = Folder::select('keyword')
                    ->where('id', $input['folder_id'])->firstOrFail();
        
        return App::isLocale('en') ? redirect('/admin/articles/'.$parent_keyword->keyword.'/page/1/'.
                                     $input['sorting_show_invisible']."/".$input['sorting_sorting_mode']."/".
                                     $input['sorting_folders_or_articles_first']) : 
                                     redirect('/ru/admin/articles/'.$parent_keyword->keyword.'/page/1'.
                                     $input['sorting_show_invisible']."/".$input['sorting_sorting_mode']."/".
                                     $input['sorting_folders_or_articles_first']);
    }
}
