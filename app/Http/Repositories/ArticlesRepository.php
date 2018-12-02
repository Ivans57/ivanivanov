<?php

namespace App\Http\Repositories;

class FolderLinkForView {
    public $keyWord;
    public $folderName;
}
        
class FolderAndArticleForView {
    public $keyWord;
    public $caption;
    public $type;
}

class FolderAndArticleForViewFullInfoForPage {
    public $folder_name;
    public $head_title;
    public $folders_and_articles_total_number;
    public $foldersAndArticles;
    public $articleAmount;
    public $folderParents;
    public $folders_and_articles_number_of_pages;
    public $folders_and_articles_current_page;
    public $folders_and_articles_previous_page;
    public $folders_and_articles_next_page;
}

class ArticleForView {
    public $article;
    public $articleParents;
}

class ArticlesRepository {
    
    public function getAllFolders($item_amount){
        $folder_links = \App\Folder::where('included_in_folder_with_id', '=', NULL)->where('is_visible', '=', 1)->latest()->paginate($item_amount);
        
        return $folder_links;
    }
    
    //need to make methods for this method
    public function getFolder($items_amount_per_page, $keyword, $page){
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $folder = \App\Folder::where('keyword', $keyword)->first();
        
        $included_articles = \App\Article::where('folder_id', $folder->id)->get();
        
        //Here we are calling method which will merge all pictures and folders from selected folder into one array
        $folders_and_articles_full = $this->get_included_folders_and_articles(\App\Folder::where('included_in_folder_with_id', '=', $folder->id)->get(), $included_articles);
        
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator
        
        //We need the object below which will contatin an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $folders_and_articles_full_info = new FolderAndArticleForViewFullInfoForPage();
        
        //Below we need to check if the folder has any parent folder.
        //If it does, Path Panel should be displayed
        //Need a bit to reorganize it.
        //We do not need property folderHasParent
        // We need property to keep all parents and this property can be checked in view
        if($folder->included_in_folder_with_id === NULL) {
            $folders_and_articles_full_info->folderParents = 0;
        }
        else {
            $folders_and_articles_full_info->folderParents = array_reverse($this->get_folders_and_articles_parents_for_view($folder->included_in_folder_with_id));
        }
        
        //We need this to know if we will have any article on the page.
        //Depending on if we have them or not, we will have some ceratin view of contents.
        $folders_and_articles_full_info->articleAmount = count($included_articles);
        
        $folders_and_articles_total_number = count($folders_and_articles_full);
        $folders_and_articles_full_info->folders_and_articles_total_number = $folders_and_articles_total_number;
        $folders_and_articles_full_info->folder_name = $folder->keyword;
        $folders_and_articles_full_info->head_title = $folder->folder_name;
        
        //The following information we can have only if we have at least one item in selected folder
        if($folders_and_articles_total_number > 0) {
        if ($folders_and_articles_full_info->articleAmount < 1) {   
            $folders_and_articles_pages = array_chunk($folders_and_articles_full, $items_amount_per_page, false);
        }
        else {
            //Actually we don't need this condition, but I saved it in case we change items amount
            //on one page with a list view.
            $folders_and_articles_pages = array_chunk($folders_and_articles_full, $items_amount_per_page, false);
        }
        $folders_and_articles_full_info->folders_and_articles_number_of_pages = count($folders_and_articles_pages);
        $folders_and_articles_current_page_for_pagination = $page - 1;
        $folders_and_articles_full_info->foldersAndArticles = $folders_and_articles_pages[$folders_and_articles_current_page_for_pagination];
        $folders_and_articles_full_info->folders_and_articles_current_page = $page;
        $folders_and_articles_full_info->folders_and_articles_previous_page = $folders_and_articles_full_info->folders_and_articles_current_page - 1;
        $folders_and_articles_full_info->folders_and_articles_next_page = $folders_and_articles_full_info->folders_and_articles_current_page + 1;
        }
        
        return $folders_and_articles_full_info;
    }
    
    public function getArticle($keyword){
        
        $articles_full_info = new ArticleForView();
        
        $articles_full_info->article = \App\Article::where('keyword', '=', $keyword)->first();
        $articles_full_info->articleParents = array_reverse($this->get_folders_and_articles_parents_for_view($articles_full_info->article->folder_id));
        
        return $articles_full_info;
    }
    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_folders_and_articles($included_folders, $articles){
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $folders_and_articles_full = array();       
        $included_folders_count = count($included_folders);
        
        for($i = 0; $i < $included_folders_count; $i++) {
            if ($included_folders[$i]->is_visible) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $included_folders[$i]->keyword;
            $folders_and_articles_full[$i]->caption = $included_folders[$i]->folder_name;
            $folders_and_articles_full[$i]->type = 'folder';
            }
        }           
        
        for($i = $included_folders_count; $i < count($articles)+$included_folders_count; $i++) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $articles[$i-$included_folders_count]->keyword;
            $folders_and_articles_full[$i]->caption = $articles[$i-$included_folders_count]->article_title;
            $folders_and_articles_full[$i]->type = 'article';
        }
        
        return $folders_and_articles_full;
    }
    
    private function get_folders_and_articles_parents_for_view($id) {
        
        $parent_folder = \App\Folder::where('id', $id)->first();
        
        $parent_folder_for_view = new FolderLinkForView();
        
        $parent_folder_for_view->keyWord = $parent_folder->keyword;
        $parent_folder_for_view->folderName = $parent_folder->folder_name;
        
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
}
