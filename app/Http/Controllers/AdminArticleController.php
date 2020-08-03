<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Repositories\CommonRepository;
use App\Http\Repositories\ArticlesRepository;
use App\Folder;

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
    
    public function create($parent_keyword) {     
        $main_links = $this->navigation_bar_obj->get_main_links_and_keywords_link_status($this->current_page);    
        
        if ($parent_keyword != "0") {
            $parent_info = Folder::select('id', 'folder_name')
                    ->where('keyword', '=', $parent_keyword)->firstOrFail();
        }       
        $parents = (new ArticlesRepository())->getArticlesParentsForPath($parent_info->id);
        
        return view('adminpages.articles.create_and_edit_article')->with([
            'main_links' => $main_links->mainLinks,
            'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
            'headTitle' => __('keywords.NewArticle'),
            //We need to know parent keyword to fill up Parent Search field.
            'parent_id' => ($parent_keyword != "0") ? $parent_info->id : $parent_keyword,
            'parent_name' => ($parent_keyword != "0") ? $parent_info->folder_name : null,
            'parents' => $parents,
            //We need this variable to find out which mode are we using Create or Edit
            //and then to open a view accordingly with a chosen mode.
            'create_or_edit' => 'create',
            //The line below is required for parent search and select and is being used in javascript.
            'section' => 'articles',
            //The last variable is required for parents search.
            //It will work when creating or editing album or folder in a directory mode,
            //when user won't see the full list of directories due to some restrictions
            //and it work when creating or editing picture or articles in a file mode,
            //when user will see a full list of all albums and folders.
            'mode' => 'file'
            ]);
    }
}
