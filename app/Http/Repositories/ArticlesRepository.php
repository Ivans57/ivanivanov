<?php

namespace App\Http\Repositories;
use App\Http\Repositories\CommonRepository;
//The repository below is required for converting BBCode to HTML 
//and processing these codes when opening an article.
use App\Http\Repositories\ArticleProcessingRepository;
use \App\Article;
use \App\Folder;
use \App\FolderData;

class FolderLinkForView {
    public $keyWord;
    public $name;
}
        
class FolderAndArticleForView {
    public $keyWord;
    public $caption;
    public $createdAt;
    public $updatedAt;
    public $isVisible;    
    public $type;
}

class FolderAndArticleForViewFullInfoForPage {
    public $folder_name;
    public $head_title;
    public $foldersAndArticles;
    public $articleAmount;
    //This property is required for radio switch on view.
    //In case this property is 0, that radio switch is not required to display.
    public $folderAmount;
    public $folderParents;
    //Two properties below are required to show correctly display_invisible element.
    public $allArticlesAmount;
    public $allFoldersAmount;
    public $folderNestingLevel;
    public $sorting_asc_or_desc;
    public $total_number_of_items;
    public $paginator_info;
}

class ArticleForView {
    public $article;
    public $articleParents;
}

//This class is required for search.
class FoldersWithPaginationInfo {
    public $all_folders_count;
    //The propery below is required to display bisibility checkbox properly.
    public $all_folders_count_including_invisible;
    public $folders_on_page;
    public $sorting_asc_or_desc;
    public $paginator_info;   
}

class ArticlesRepository {
    
    //The null below for the last two arguments is just temporary!
    public function getAllLevelZeroFolders($items_amount_per_page, $including_invisible, $sort_by_field = null, $asc_desc = null){     
        if ($including_invisible) {
            //The condition below is just temporary!            
            $folder_links = Folder::where('included_in_folder_with_id', '=', NULL)
                            ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')
                            ->paginate($items_amount_per_page);
        } else {
            //The condition below is just temporary!
            $folder_links = Folder::where('included_in_folder_with_id', '=', NULL)->where('is_visible', '=', 1)
                            ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->paginate($items_amount_per_page);
        }      
        return $folder_links;
    }
    
    public function getAllFoldersForSearch($search_text, $including_invisible, $sort_by_field = null, $asc_desc = null){
        
        if ($including_invisible) {
            $folders = (Folder::where('folder_name', 'LIKE', '%'.$search_text.'%')->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        } else {
            //The condition below is just temporary!
            $folders = (Folder::where('folder_name', 'LIKE', '%'.$search_text.'%')->where('is_visible', '=', 1)
                             ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        }      
        return $folders;
    }
    
    public function getAllArticlesForSearch($search_text, $including_invisible, $sort_by_field = null, $asc_desc = null){
        
        if ($including_invisible) {
            $articles = (Article::where('article_title', 'LIKE', '%'.$search_text.'%')->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        } else {
            //The condition below is just temporary!
            $articles = (Article::where('folder_name', 'LIKE', '%'.$search_text.'%')->where('is_visible', '=', 1)
                             ->orderBy(($sort_by_field) ? $sort_by_field : 'created_at', 
                            ($asc_desc) ? $asc_desc : 'desc')->get());
        }      
        return $articles;
    }
    
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album.
    public function showFolderView($section, $page, $keyword, $items_amount_per_page, $main_links, 
                                    $is_admin_panel, $including_invisible, $sorting_mode = null, $folders_or_articles_first = null) {       
        $common_repository = new CommonRepository();
        //The condition below fixs a problem when user enters as a number of page some number less then 1.
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $folders_and_articles_full_info = $this->getFolder($keyword, $page, $items_amount_per_page, 
                                                                $including_invisible, $sorting_mode, $folders_or_articles_first);
            //We need to do the check below in case user enters a page number more tha actual number of pages
            if ($page > $folders_and_articles_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, 
                                                            $folders_and_articles_full_info->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                        $items_amount_per_page, $including_invisible, $sorting_mode, $folders_or_articles_first);
            }
        }
    }
    
    //We need the method below to clutter down showFolderView method
    private function get_view($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                $items_amount_per_page, $including_invisible, $sorting_mode = null, $folders_or_articles_first = null) {
        if ($is_admin_panel) {
            return $this->get_view_for_admin_panel($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                                    $items_amount_per_page, $including_invisible, $sorting_mode, $folders_or_articles_first);
        } else {
            return $this->get_view_for_website($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                                    $items_amount_per_page, $sorting_mode, $folders_or_articles_first);                   
        }
    }
    
    //The function below is required to simplify get_view function.
    private function get_view_for_admin_panel($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                              $items_amount_per_page, $including_invisible, $sorting_mode = null, 
                                              $folders_or_articles_first = null) {
        return view('adminpages.folders.adminfolder')->with([
                //Below main website links.
                'main_ws_links' => $main_links->mainWSLinks,
                //Below main admin panel links.
                'main_ap_links' => $main_links->mainAPLinks,
                'headTitle' => $folders_and_articles_full_info->head_title,         
                'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
                'articleAmount' => $folders_and_articles_full_info->articleAmount,
                'folderAmount' => $folders_and_articles_full_info->folderAmount,
                //The variables below are required to display view properly depending on whether 
                //the folder has both articles and folders included, or has only articles or folders included.
                'allArticlesAmount' => $folders_and_articles_full_info->allArticlesAmount,
                'allFoldersAmount' => $folders_and_articles_full_info->allFoldersAmount,
                'sorting_asc_or_desc' => $folders_and_articles_full_info->sorting_asc_or_desc,
                'parents' => $folders_and_articles_full_info->folderParents,
                'nesting_level' => $folders_and_articles_full_info->folderNestingLevel,
                'pagination_info' => $folders_and_articles_full_info->paginator_info,
                'total_number_of_items' => $folders_and_articles_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword,
                'sorting_mode' => ($sorting_mode) ? $sorting_mode : 'sort_by_creation_desc',
                //The variable below is required to keep previous 
                //sorting options in case all elements are invisible and user wants to make them visible again.
                'sorting_method_and_mode' => ($sorting_mode) ? $sorting_mode : '0',
                'directories_or_files_first' => ($folders_or_articles_first) ? $folders_or_articles_first : 'folders_first',
                'show_invisible' => $including_invisible == 1 ? 'all' : 'only_visible',
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel,
                'what_to_search' => 'folders'
                ]);
    }
       
    //The function below is required to simplify get_view function.
    private function get_view_for_website($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, 
                                            $items_amount_per_page, $sorting_mode = null, $folders_or_articles_first = null) {
        return view('pages.folder')->with([
                'main_links' => $main_links,
                'headTitle' => $folders_and_articles_full_info->head_title,
                'folderName' => $folders_and_articles_full_info->folder_name,           
                'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
                'articleAmount' => $folders_and_articles_full_info->articleAmount,
                'folderAmount' => $folders_and_articles_full_info->folderAmount,
                'parents' => $folders_and_articles_full_info->folderParents,            
                'pagination_info' => $folders_and_articles_full_info->paginator_info,
                'total_number_of_items' => $folders_and_articles_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                //parent_keyword is required for sorting.
                'parent_keyword' => $keyword,
                'sorting_mode' => ($sorting_mode) ? $sorting_mode : 'sort_by_creation_desc',
                'directories_or_files_first' => ($folders_or_articles_first) ? $folders_or_articles_first : 'folders_first',
                //is_admin_panel is required for paginator.
                'is_admin_panel' => $is_admin_panel
                ]);
    }
    
    //This function returns all folders of some level elements except for 0 level elements.
    //There is a separate function for level zero elements, because they need to be paginated.
    //The null below for the last two arguments is just temporary!
    public function getIncludedFolders($folder, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $included_folders = Folder::where('included_in_folder_with_id', '=', $folder->id)->orderBy($sort_by_field, $asc_desc)->get();
        } else {
            $included_folders = Folder::where('included_in_folder_with_id', '=', $folder->id)
                        ->where('is_visible', '=', 1)->orderBy($sort_by_field, $asc_desc)->get();
        }
        return $included_folders;
    }
    
    public function getIncludedArticles($folder, $including_invisible, $sort_by_field = null, $asc_desc = null) {
        if ($including_invisible) {
            $included_articles = Article::where('folder_id', $folder->id)->orderBy($sort_by_field, $asc_desc)->get();
        } else {
            $included_articles = Article::where('folder_id', $folder->id)->where('is_visible', '=', 1)
                    ->orderBy($sort_by_field, $asc_desc)->get();
        }
        return $included_articles;
    }
    
    private function getFolder($keyword, $page, $items_amount_per_page, $including_invisible, $sorting_mode = null, 
                                                                                    $folders_or_articles_first = null) {
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $folder = Folder::where('keyword', $keyword)->first();       
        $nesting_level = FolderData::where('items_id', $folder->id)->select('nesting_level')->first();
        
        $for_sort_and_pagination = new CommonRepository();
        
        $included_folders = $for_sort_and_pagination->sort_for_albums_or_articles("0", $items_amount_per_page, $sorting_mode, $including_invisible, 
                                                                'included_folders', $folder);        
        $included_articles = $for_sort_and_pagination->sort_for_albums_or_articles("0", $items_amount_per_page, $sorting_mode, $including_invisible, 
                                                                'included_articles', $folder);
        
        //Here we are calling method which will merge all articles and folders from selected folder into one array.
        $folders_and_articles_full = $this->get_included_folders_and_articles($included_folders["directories_or_files"], 
                                        $included_articles["directories_or_files"], $folders_or_articles_first);
         
        //We need the object below which will contatin an array of needed folders 
        //and articles and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $folders_and_articles_full_info = new FolderAndArticleForViewFullInfoForPage();
        
        //Two lines below are required to show correctly display_invisible elements.
        $folders_and_articles_full_info->allArticlesAmount = Article::where('folder_id', '=', $folder->id)->count();
        $folders_and_articles_full_info->allFoldersAmount = Folder::where('included_in_folder_with_id', '=', $folder->id)->count();
        
        $folders_and_articles_full_info->sorting_asc_or_desc = $included_folders["sorting_asc_or_desc"];
                
        $folders_and_articles_full_info->folderNestingLevel = $nesting_level->nesting_level;
        
        //Below we need to check if the folder has any parent folder.
        //If it does, Path Panel should be displayed
        //Need a bit to reorganize it.
        //We do not need property folderHasParent
        // We need property to keep all parents and this property can be checked in view
        if($folder->included_in_folder_with_id === NULL) {
            $folders_and_articles_full_info->folderParents = 0;
        }
        else {
            $folders_and_articles_full_info->folderParents = array_reverse(
                    $this->get_folders_and_articles_parents_for_view($folder->included_in_folder_with_id));
        }
        
        //We need this to know if we will have any article on the page.
        //Depending on if we have them or not, we will have some ceratin view of contents.
        $folders_and_articles_full_info->articleAmount = count($included_articles["directories_or_files"]); 
        $folders_and_articles_full_info->folderAmount = count($included_folders["directories_or_files"]);
        $folders_and_articles_full_info->folder_name = $folder->keyword;
        $folders_and_articles_full_info->head_title = $folder->folder_name;
        $folders_and_articles_full_info->total_number_of_items = count($folders_and_articles_full);
        
        //The following information we can have only if we have at least one item in selected folder
        if(count($folders_and_articles_full) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $folders_and_articles_full_cut_into_pages = array_chunk($folders_and_articles_full, $items_amount_per_page, false);
            $folders_and_articles_full_info->paginator_info = $for_sort_and_pagination
                                                                ->get_paginator_info($page, $folders_and_articles_full_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            if ($folders_and_articles_full_info->paginator_info->number_of_pages >= $page) {
                //The line below selects the page we need, as computer counts from 0, we need to subtract 1
                $folders_and_articles_full_info->foldersAndArticles = $folders_and_articles_full_cut_into_pages[$page-1];
            }
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showFolderView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $folders_and_articles_full_info->paginator_info = new Paginator();
            $folders_and_articles_full_info->paginator_info->number_of_pages = 1;
        }
        
        return $folders_and_articles_full_info;
    }
    
    public function getArticle($keyword) {
        
        $articles_full_info = new ArticleForView();       
        $articles_full_info->article = Article::where('keyword', '=', $keyword)->first();
        
        //Below before assigning a value to article_body, need to process it, as an article code is getting saved and stored as BBCode and
        //it is supposed to be converted to html.
        $articles_full_info->article->article_body = (new ArticleProcessingRepository())->articleCodeProcessing($articles_full_info->article->article_body);      
        $articles_full_info->articleParents = array_reverse($this->get_folders_and_articles_parents_for_view($articles_full_info->article->folder_id));             
        return $articles_full_info;
    }
    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and articles.
    private function get_included_folders_and_articles($included_folders, $articles, $folders_or_articles_first = null) {       
        //We need to merge included folders and articles to show them in selected folder on the same page.
        if ($folders_or_articles_first === "articles_first") {
            $folders_and_articles_full = array_merge($this->get_included_articles($articles, count($included_folders)), 
                                                 $this->get_included_folders($included_folders, count($included_folders)));
        } else {
            $folders_and_articles_full = array_merge($this->get_included_folders($included_folders, count($included_folders)), 
                                                 $this->get_included_articles($articles, count($included_folders)));
        }
        
        return $folders_and_articles_full;
    }
    
    //We need to make separate functions for folders and articles parts of common array, because depending on user wish
    //on the website might be required to display folders before articles or opposite.
    private function get_included_folders($included_folders, $included_folders_count) {
        $folders_and_articles_full = array();       
        
        for($i = 0; $i < $included_folders_count; $i++) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $included_folders[$i]->keyword;
            $folders_and_articles_full[$i]->caption = $included_folders[$i]->folder_name;
            $folders_and_articles_full[$i]->createdAt = $included_folders[$i]->created_at;
            $folders_and_articles_full[$i]->updatedAt = $included_folders[$i]->updated_at;
            $folders_and_articles_full[$i]->isVisible = $included_folders[$i]->is_visible;
            $folders_and_articles_full[$i]->type = 'folder';
        }
        return $folders_and_articles_full;
    }
    
    //We need to make separate functions for folders and articles parts of common array, because depending on user wish
    //on the website might be required to display folders before articles or opposite.
    private function get_included_articles($articles, $included_folders_count) {
        $folders_and_articles_full = array();
        
        for($i = $included_folders_count; $i < count($articles)+$included_folders_count; $i++) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $articles[$i-$included_folders_count]->keyword;
            $folders_and_articles_full[$i]->caption = $articles[$i-$included_folders_count]->article_title;
            $folders_and_articles_full[$i]->createdAt = $articles[$i-$included_folders_count]->created_at;
            $folders_and_articles_full[$i]->updatedAt = $articles[$i-$included_folders_count]->updated_at;
            $folders_and_articles_full[$i]->isVisible = $articles[$i-$included_folders_count]->is_visible;
            $folders_and_articles_full[$i]->type = 'article';
        }
        return $folders_and_articles_full;
    }
    
    private function get_folders_and_articles_parents_for_view($id) {
        
        $parent_folder = Folder::where('id', $id)->first();
        
        $parent_folder_for_view = new FolderLinkForView();
        
        $parent_folder_for_view->keyWord = $parent_folder->keyword;
        $parent_folder_for_view->name = $parent_folder->folder_name;
        
        $parent_folders_for_view = array();
        
        $parent_folders_for_view[] = $parent_folder_for_view;
        
        if ($parent_folder->included_in_folder_with_id === NULL) {
            return $parent_folders_for_view;
        }
        else {
            $folders_and_articles_parents_for_view = $this->get_folders_and_articles_parents_for_view($parent_folder->included_in_folder_with_id);
            foreach ($folders_and_articles_parents_for_view as $folders_and_articles_parent_for_view) {
                $parent_folders_for_view[] = $folders_and_articles_parent_for_view;
            }
            return $parent_folders_for_view;
        }
    }
    
    //This function is required when make a new article or edit existing one
    //to show the article's path.
    public function getArticlesParentsForPath($parent_folders_id) {
        return array_reverse($this->get_folders_and_articles_parents_for_view($parent_folders_id));;
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //folder keyword or editing existing.
    public function get_all_folders_keywords() {
        
        $all_folders_keywords = Folder::all('keyword');       
        $folders_keywords_array = array();
        
        foreach ($all_folders_keywords as $folder_keyword) {
            array_push($folders_keywords_array, $folder_keyword->keyword);
        }    
        return $folders_keywords_array;   
    }
    
    //This function is used for search.
    public function getFoldersFromSearch($search_text, $page, $items_amount_per_page, $what_to_search, $show_invisible, $sorting_mode = null) {        
           
        $common = new CommonRepository();
        
        $folders_with_pagination = new FoldersWithPaginationInfo();
        
        //In the next line the data are getting extracted from the database and sorted.
        //The sixth parameter needs to pass as null to avoid confusion.
        $all_folders = $common->sort_for_albums_or_articles(1, $items_amount_per_page, $sorting_mode, 
                                                                             $show_invisible === "all" ? 1 : 0, $what_to_search, null/*parent directory*/, $search_text);
        
        $folders_with_pagination->all_folders_count = sizeof($all_folders["directories_or_files"]);
        
        $folders_with_pagination->all_folders_count_including_invisible = Folder::where('folder_name', 'LIKE', '%'.$search_text.'%')->count();
        
        $folders_with_pagination->sorting_asc_or_desc = $all_folders["sorting_asc_or_desc"];
        
        //Later keywords array has to be chunked to separate pieces for pagination.
        //When getting data from the database it is not a pure array, it is an object and it cannot be chunked.
        //For that reason these data need to be converted to an array.
        $all_folders_array = [];
        
        foreach ($all_folders["directories_or_files"] as $one_folder){
           array_push($all_folders_array, $one_folder); 
        }
        
        //The following information we can have only if we have at least one item.
        if($folders_with_pagination->all_folders_count > 0) {
            //The line below cuts all data into pages.
            //We can do it only if we have at least one item in the array of the full data.
            $folders_cut_into_pages = array_chunk($all_folders_array, $items_amount_per_page, false);
            $folders_with_pagination->paginator_info = $common->get_paginator_info($page, $folders_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            $folders_with_pagination->folders_on_page = $folders_with_pagination->paginator_info->number_of_pages >= $page ? 
                                                                $folders_cut_into_pages[$page-1] : null;
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showAlbumView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $folders_with_pagination->paginator_info = new Paginator();
            $folders_with_pagination->paginator_info->number_of_pages = 1;
        }
        
        return $folders_with_pagination;
    }
}
