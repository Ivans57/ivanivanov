<?php

namespace App\Http\Repositories;

use App\Http\Repositories\CommonRepository;
use App;


class AlbumLinkForView {
    public $keyWord;
    public $name;
}  
        
class AlbumAndPictureForView {
    public $keyWord;
    public $caption;
    public $type;
    public $fileExtension;
}

class AlbumAndPictureForViewFullInfoForPage {
    public $album_name;
    public $head_title;
    public $albumsAndPictures;
    public $albumParents;
    public $albumNestingLevel;
    //It is better to keep this property here,
    //so in case of empty items array we don't need
    //to make an object.
    public $total_number_of_items;
    public $paginator_info;   
}

class AlbumParentsData {
    public $parentsDataArray;
    public $paginationInfo;
}

class PaginationInfoForParentSearch {
    public $previousPage;
    public $nextPage;
}

class ParentDropDownListElement {
    public $AlbumId;
    public $AlbumName;
    public $HasChildren;
    //The last two properties are used only when making opened list to indicate 
    //whether a line is closed or open to show a caret and its events in 
    //a proper state and mark selected record.
    public $isOpened = false;
    public $inFocus = false;
}

class AlbumsRepository {
    
    public function getAllAlbums($items_amount_per_page, $including_invisible){
        
        if ($including_invisible) {
            $album_links = \App\Album::where('included_in_album_with_id', '=', NULL)->orderBy('created_at','DESC')->paginate($items_amount_per_page);
        } else {
            $album_links = \App\Album::where('included_in_album_with_id', '=', NULL)->where('is_visible', '=', 1)->orderBy('created_at','DESC')->paginate($items_amount_per_page);
        }
        return $album_links;
    }
       
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album
    public function showAlbumView($section, $page, $keyword, $items_amount_per_page, $main_links, $is_admin_panel, $including_invisible){
        
        $common_repository = new CommonRepository();
        //The condition below fixs a problem when user enters as a number of page some number less then 1
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $albums_and_pictures_full_info = $this->getAlbum($keyword, $page, $items_amount_per_page, $including_invisible);
            //We need to do the check below in case user enters a page number more than actual number of pages
            if ($page > $albums_and_pictures_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, $albums_and_pictures_full_info->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, $items_amount_per_page);
            }
        }
    }
    
    //We need this function for Album Parent search field when create or edit album.
    public function getParents($localization, $page, $album_to_find, $albums_to_exclude_keyword) {
              
        $parents = new AlbumParentsData();
        
        $records_to_show = 10;
        
        $parents_from_query = $this->get_parents_from_query($localization, $page, $album_to_find, $albums_to_exclude_keyword, 
                                                            $records_to_show);
        $parents->paginationInfo = $this->get_pagination_info($parents_from_query);

        $parents->parentsDataArray = array();
        
        if ($album_to_find && count($parents_from_query) > 0) {
            
            foreach ($parents_from_query as $album) {
                $album_path = $this->get_full_album_path($album->id, "");
                $parent_data_array = [$album->id, $album_path];
                array_push($parents->parentsDataArray, $parent_data_array);
            }
        }
             
        return $parents;
    }
    
    //We need this function for Album Parent dropdown list when create or edit album.
    public function getParentList($localization, $page, $parent_id, $parent_node_id, $keyword_of_album_to_exclude) {
        
        $parents = new AlbumParentsData();        
        $records_to_show = 10;
        
        //parent_id is an andicator whether an albums is located on 0 nestenig level or no.
        //0 nesting level albums are easier to process.
        if ($parent_id == 0 || $parent_node_id !== null) {
            //Getting closed parent list only when creating or editing an album in root album.
            $parents = $this->get_closed_parent_list($localization, $page, $records_to_show, 
                                                    ($parent_node_id == 0 ? $parent_node_id = null : $parent_node_id), 
                                                    $keyword_of_album_to_exclude);
            
        } else {
            //Getting opened parent list only when creating or editing an album 
            //in in any album except of the root album.
            $parents = $this->get_opened_parent_list($localization, $records_to_show, $parent_id, $keyword_of_album_to_exclude);
        }
        
        return $parents;
    }
    
    //This function will be called when user is creating a new album on level 0,
    //or when is editing an album which is located on level 0.
    private function get_closed_parent_list($localization, $page, $records_to_show, $parent_node_id, $keyword_of_album_to_exclude) {
        $parents = new AlbumParentsData();
        
        //We need this for additional check whether a being checked item has children.
        $id_of_album_to_exclude = null;
        if ($keyword_of_album_to_exclude) {
            $id_of_album_to_exclude = \App\Album::select('id')->where('keyword', $keyword_of_album_to_exclude)->firstOrFail();
        }
        //Below we need to get some information which will help to filter albums with unacceptable nesting level
        //as nesting levels amount is limited.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($keyword_of_album_to_exclude);
        
        if ($localization === "en") {
            $parent_list_from_query = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                        'en_albums_data.nesting_level')
                                ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                                ->where('en_albums.included_in_album_with_id', $parent_node_id)
                                ->where('en_albums.keyword', '!=', $keyword_of_album_to_exclude)
                                ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                        'ru_albums_data.nesting_level')
                                ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                                ->where('ru_albums.included_in_album_with_id', $parent_node_id)
                                ->where('ru_albums.keyword', '!=', $keyword_of_album_to_exclude)
                                ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }

        $parent_list_array = array();

        if (count($parent_list_from_query) > 0) {

            foreach ($parent_list_from_query as $album) {
                $parent_data_array = new ParentDropDownListElement();
                $parent_data_array->AlbumId = $album->id;
                $parent_data_array->AlbumName = $album->album_name;
                
                //We need to use this function as due to some filters (selfinclusion and nesting levels) we might need 
                //to exclude some children from item's children list.
                $parent_data_array->HasChildren = true;
                if ($album->children === null || $album->nesting_level === ($max_acceptable_nest_level - 1)) {
                    $parent_data_array->HasChildren = false;
                } else if ($id_of_album_to_exclude) {
                    $parent_data_array->HasChildren = $this->check_for_children($album->id, $id_of_album_to_exclude->id);
                }
                
                array_push($parent_list_array, $parent_data_array);
            }
        }
        $parents->parentsDataArray = $parent_list_array;
        $parents->paginationInfo = $this->get_pagination_info($parent_list_from_query);
        
        return $parents;
    }
    
    //This function will be called when user is creating a new album on level 0,
    //or when is editing an album which is located on level 0.
    private function get_opened_parent_list($localization, $records_to_show, $parent_id, $keyword_of_album_to_exclude) {
        $parents = new AlbumParentsData();
        //First of all need to make an array of all ancestors of the item.
        //There is a special field for them in data table, but their sequence might be wrong.
        $parent_id_array = array();
        array_push($parent_id_array, intval($parent_id));
        $parent_ids = $this->get_all_parents_ids_for_item($parent_id, $parent_id_array);

        //Comparing to create option, ecah of these properties will have an array, as
        //on client's side javascript will take each array and form a list of items from it
        //on some certain nesting level, then wi;ll take elements of the next nesting level.
        $parentsDataArrayReversed = array();
        $parentsPaginationInfoReversed = array();
        
        for ($i = 0; $i < count($parent_ids); $i++) {
            $parents_and_pagination_info_for_array = $this->get_parents_and_pagination_info_for_array($localization, $parent_ids[$i], 
                                                                                                    $records_to_show, $i,
                                                                                                    $keyword_of_album_to_exclude);
            array_push($parentsDataArrayReversed, $parents_and_pagination_info_for_array->parentsDataArray);
            array_push($parentsPaginationInfoReversed, $parents_and_pagination_info_for_array->paginationInfo);
        }
        
        $parents->parentsDataArray = array_reverse($parentsDataArrayReversed);
        $parents->paginationInfo = array_reverse($parentsPaginationInfoReversed);
        
        return $parents;
    }
    
    //This function extracts all ancestors of the selected record.
    private function get_all_parents_ids_for_item($item_id, $parent_ids) {
        $parent_id_new = \App\Album::select('included_in_album_with_id')->where('id', $item_id)->firstOrFail();
        if ($parent_id_new->included_in_album_with_id !== null) {
            array_push($parent_ids, $parent_id_new->included_in_album_with_id);
            $parent_ids = $this->get_all_parents_ids_for_item($parent_id_new->included_in_album_with_id, $parent_ids);
        }
        return $parent_ids;
    }
    
    //This function will get parent information and pagination information for
    //parents and their pagination information array, which will be required for
    //parent dropdown list for Album edit window.
    private function get_parents_and_pagination_info_for_array($localization, $parent_id, $records_to_show, $iteration, 
                                                            $keyword_of_album_to_exclude) {
        $parents_for_array = new AlbumParentsData();
        $parent_id_of_parent = \App\Album::select('included_in_album_with_id')->where('id', $parent_id)->firstOrFail();
        
        //Below we need to get some information which will help to filter albums with unacceptable nesting level
        //as nesting levels amount is limited.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($keyword_of_album_to_exclude);
        
        //We need an id of album which we need to exclude from parents list to find all its direct children.
        $id_of_album_to_exclude = null;
        if ($keyword_of_album_to_exclude) {
            $id_of_album_to_exclude = \App\Album::select('id')->where('keyword', $keyword_of_album_to_exclude)->firstOrFail();
        }
          
        //These request we need to do only to get a data for pagination, because required record might be not on the first page.
        if ($localization === "en") {
            $parent_list_from_query_for_data = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                                'en_albums_data.nesting_level')
                            ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                            ->where('en_albums.keyword', '!=', $keyword_of_album_to_exclude)
                            ->where('en_albums.included_in_album_with_id', $parent_id_of_parent->included_in_album_with_id)
                            ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_albums.created_at','DESC')->get();
        } else {
            $parent_list_from_query_for_data = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                                'ru_albums_data.nesting_level')
                            ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                            ->where('ru_albums.keyword', '!=', $keyword_of_album_to_exclude)
                            ->where('ru_albums.included_in_album_with_id', $parent_id_of_parent->included_in_album_with_id)
                            ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('ru_albums.created_at','DESC')->get();
        } 
            
        $record_location = 0;
            
        $items_amount = count($parent_list_from_query_for_data);
            
        for ($i = 0; $i <= $items_amount; $i++) {
            if ($parent_list_from_query_for_data[$i]->id == $parent_id) {
                $record_location = $i + 1;
                $i = $items_amount;
            }
        }
            
        //ceil function chooses the next bigger value of a decimal fraction, e.g. 5.1 => 6
        //intval converts float which we get from ceil to int.
        $page = intval(ceil($record_location/$records_to_show));
        if ($localization === "en") {    
            $parent_list_from_query = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                        'en_albums_data.nesting_level')
                            ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                            ->where('en_albums.keyword', '!=', $keyword_of_album_to_exclude)
                            ->where('en_albums.included_in_album_with_id', $parent_id_of_parent->included_in_album_with_id)
                            ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                    'ru_albums_data.nesting_level')
                        ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                        ->where('ru_albums.keyword', '!=', $keyword_of_album_to_exclude)
                        ->where('ru_albums.included_in_album_with_id', $parent_id_of_parent->included_in_album_with_id)
                        ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
            
        $parents_for_array->parentsDataArray = array();
            
        foreach ($parent_list_from_query as $album) {
            $parent_data_array = new ParentDropDownListElement();
            $parent_data_array->AlbumId = $album->id;
            $parent_data_array->AlbumName = $album->album_name;
            
            //We need to use this function as due to some filters we might need 
            //to exclude some children from item's children list.
            $parent_data_array->HasChildren = true;
            if ($album->children === null || $album->nesting_level === ($max_acceptable_nest_level - 1)) {
                $parent_data_array->HasChildren = false;
            } else if ($id_of_album_to_exclude) {
                $parent_data_array->HasChildren = $this->check_for_children($album->id, $id_of_album_to_exclude->id);
            }
            
            if ($album->id == $parent_id && $iteration == 0) {
                $parent_data_array->inFocus = true;
            } else if ($album->id == $parent_id && $iteration > 0) {
                $parent_data_array->isOpened = true;
            }
            array_push($parents_for_array->parentsDataArray, $parent_data_array);
        }
            
        $parents_for_array->paginationInfo = $this->get_pagination_info($parent_list_from_query);
        
        return $parents_for_array;
    }
    
    //We need to use this function as due to some filters we might need 
    //to exclude some children from item's children list.
    private function check_for_children($album_id, $id_of_album_to_exclude) {
        
        $items_direct_children = \App\Album::select('id')
                        ->where('included_in_album_with_id', '=', $album_id)
                        ->orderBy('created_at','DESC')->get();
        $hasChildren = false;       
        if (count($items_direct_children) > 1) {
            $hasChildren = true;
        } else {
            if ($items_direct_children[0]->id !== $id_of_album_to_exclude) {
                $hasChildren = true;
            }
        }      
        return $hasChildren;
    }
    
    //This function gets a pagination information for Parent search when create or edit album.
    //We cannot display all albums, as there might be a too many of them, which will make the system slow.
    //To avoid it, need to split an information in portions, that's why need to do a pagination.
    private function get_pagination_info($parents_from_query) {
        
        $pagination_info = new PaginationInfoForParentSearch();
        
        if ($parents_from_query->previousPageUrl() !== null) {
            $pagination_info->previousPage = (int)substr($parents_from_query->previousPageUrl(), -1);
        }

        if ($parents_from_query->nextPageUrl() !== null) {
            //In case user did not enter anything in search, this property will be overriden in controller
            //before sending json response.
            $pagination_info->nextPage = (int)substr($parents_from_query->nextPageUrl(), -1);
        }
        return $pagination_info;
    }
    
    //We need this function to simplify getParents function.
    private function get_parents_from_query($localization, $page, $album_to_find, $albums_to_exclude_keyword, $records_to_show) { 
        
        $items_children = $this->get_albums_children_array($albums_to_exclude_keyword);
        
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($albums_to_exclude_keyword);
        
        if ($localization === "en") {
            $parents = \App\Album::select('en_albums.id', 'en_albums.keyword', 'en_albums.album_name')
                        ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                        ->where('album_name', 'LIKE', "%$album_to_find%")
                        ->where('en_albums.keyword', '!=', $albums_to_exclude_keyword)
                        ->whereNotIn('en_albums.id', $items_children)
                        ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parents = \App\Album::select('ru_albums.id', 'ru_albums.keyword', 'ru_albums.album_name')
                        ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                        ->where('album_name', 'LIKE', "%$album_to_find%")
                        ->where('ru_albums.keyword', '!=', $albums_to_exclude_keyword)
                        ->whereNotIn('ru_albums.id', $items_children)
                        ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
            
        return $parents;
    }
    
    //We need this function to simplify getParents and get_parents_from_query functions.
    private function get_albums_children_array($albums_to_exclude_keyword) {
        if($albums_to_exclude_keyword) {
        
            //We will do two request to avoid localization check.
            $album_id = \App\Album::select('id')->where('keyword', $albums_to_exclude_keyword)->firstOrFail();
            
            $items_children_from_query = \App\AlbumData::select('children')
                    ->where('items_id', $album_id->id)->firstOrFail();

            $items_children_array = json_decode($items_children_from_query->children, true);
            
            if($items_children_array){
                $items_children = array_map('intval', $items_children_array);
            } else {
                $items_children = array();
            }
        
        } else {
            $items_children = array();
        }
        
        return $items_children;
    }
    
    //We need this function to shorten getParents function.
    private function get_max_acceptable_nest_level($albums_to_exclude_keyword = NULL) {
               
        $max_acceptable_nest_level = 7;
        
        if ($albums_to_exclude_keyword != NULL) {
            $items_id = \App\Album::where('keyword', $albums_to_exclude_keyword)->select('id')->firstOrFail();
            $items_nesting_level_and_children = \App\AlbumData::where('items_id', $items_id->id)
                    ->select('nesting_level', 'children')->firstOrFail();
            
            $items_children = json_decode($items_nesting_level_and_children->children, true);
            
            if (is_null($items_children)){
                $children_max_nest_level = $items_nesting_level_and_children->nesting_level;
            } else {
                $children_max_nest_level = $this->get_children_max_nest_level($items_children, $max_acceptable_nest_level);
            }
            
            $children_rel_max_nest_level = $children_max_nest_level - $items_nesting_level_and_children->nesting_level;
            $max_acceptable_nest_level = $max_acceptable_nest_level - $children_rel_max_nest_level;
        }
                   
        return $max_acceptable_nest_level;
    }
    
    //We need this function for Album Parent search field when create or edit album.
    //It is not enough to get just a name of the album, we need to get a full path to show.
    private function get_full_album_path($album_id, $album_path) {
        //We cannot get information from data table becuase in this case the sequence of items is important.
        $album = \App\Album::select('album_name', 'included_in_album_with_id')
                ->where('id', $album_id)->firstOrFail();
        $album_full_path = substr_replace($album_path, ' / '.$album->album_name, 0, 0);
        if ($album->included_in_album_with_id != 0){
            $album_full_path = $this->get_full_album_path($album->included_in_album_with_id, $album_full_path);
        }
        return $album_full_path;
    }
    
    //We need this function to make a drop down list for Album addition in Admin Panel
    //This function accepts one argument, because when we have a drop down list
    //in edit window, we need to exclude being changed album and its children
    //from that list, so user can't move the album into itself or its children.
    //The argument has default value NULL because the same function
    //is used for create method which can't give any argument to this function.
    public function getAllAlbumsList($albums_to_exclude_keyword = NULL){
        
        //First we need to filter out albums which cannot be parents due to their nesting level.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($albums_to_exclude_keyword);
        $albums = $this->get_all_albums_for_dp_list_from_query(NULL, $max_acceptable_nest_level, $albums_to_exclude_keyword);
      
        $albums_for_list = array();
        $albums_for_list[0] = '-';
        foreach ($albums as $album) {           
            $albums_for_list[$album->id] = $album->album_name;          
            //We need the variable below to add prefix spaces properly to each element
            //of the list, depeding on whether some particluar list of item is included
            //in some another item or no.
            $list_inclusion_level = 1;
            
            $all_included_albums = $this->get_all_included_albums($album->id, 
                    $list_inclusion_level, $max_acceptable_nest_level, $albums_to_exclude_keyword);
            if ($all_included_albums != NULL) {
                $albums_for_list = $albums_for_list + $all_included_albums;
            }
        }
        
        return $albums_for_list;
    }
    
    //We need this function to get all included albums in parent album.
    //This function accepts one argument, because when we have a drop down list
    //in edit window, we need to exclude being changed album and its children
    //from that list, so user can't move the album into itself or its children.
    //The argument has default value NULL because the same function
    //is used for create method which can't give any argument to this function.
    private function get_all_included_albums($parent_album_id, 
            $list_inclusion_level, $max_acceptable_nest_level, $albums_to_exclude_keyword = NULL) {
                     
        $included_albums = $this->get_all_albums_for_dp_list_from_query($parent_album_id, $max_acceptable_nest_level, $albums_to_exclude_keyword);
                
        $included_albums_for_list = array();
        foreach ($included_albums as $included_album) {
           
            //The variable below is required to add prefix spaces to the element
            //of the list.
            $album_name_prefix = '';
            //We need a loop below to add prefix spaces to the elements of the list 
            for ($level_in_list = 0; $level_in_list < $list_inclusion_level; $level_in_list++) {
                $album_name_prefix = $album_name_prefix.'&nbsp; &nbsp;';
            }
            
            $included_albums_for_list[$included_album->id] = $album_name_prefix.$included_album->album_name;
            
            $all_included_albums = $this->get_all_included_albums($included_album->id, $list_inclusion_level + 1, 
                    $max_acceptable_nest_level, $albums_to_exclude_keyword);
            if ($all_included_albums != NULL) {
                $included_albums_for_list = $included_albums_for_list + $all_included_albums;
            }
        }
        
        return $included_albums_for_list;
    }
    
    //We need this function to shorten get_all_included_albums function.
    private function get_all_albums_for_dp_list_from_query($parent_album_id, $max_acceptable_nest_level, $albums_to_exclude_keyword = NULL) {
        
        if (App::isLocale('en')) {
        
            $included_albums = \App\Album::select('en_albums.id', 'en_albums.album_name')
                ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                ->where('en_albums.included_in_album_with_id', '=', $parent_album_id)
                ->where('en_albums.keyword', '!=', $albums_to_exclude_keyword)
                ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                ->orderBy('en_albums.created_at','DESC')->get();
    
        } else {
        
            $included_albums = \App\Album::select('ru_albums.id', 'ru_albums.album_name')
                ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                ->where('ru_albums.included_in_album_with_id', '=', $parent_album_id)
                ->where('ru_albums.keyword', '!=', $albums_to_exclude_keyword)
                ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                ->orderBy('ru_albums.created_at','DESC')->get();
        
        }
             
        return $included_albums;
    }
    
    //We need this function to shorten get_max_acceptable_nest_level function.
    private function get_children_max_nest_level($items_children_strings, $max_acceptable_nest_level) {
               
        //Converting string array to int array.
        $items_children = array_map('intval', $items_children_strings);
        $children_nest_levels = \App\AlbumData::whereIn('items_id', $items_children)
                    ->select('nesting_level')->get();
                
        $children_max_nest_level = 0;
                
            foreach ($children_nest_levels as $children_nest_level) {
                if ($children_nest_level->nesting_level > $children_max_nest_level && 
                        $children_nest_level->nesting_level != $max_acceptable_nest_level) {
                    $children_max_nest_level = $children_nest_level->nesting_level;
                } elseif ($children_nest_level->nesting_level > $children_max_nest_level && 
                        $children_nest_level->nesting_level == $max_acceptable_nest_level) {
                    $children_max_nest_level = $children_nest_level->nesting_level;
                    break;
                        }
            }
                   
        return $children_max_nest_level;
    }
    
    //We need the method below to clutter down showAlbumView method
    private function get_view($is_admin_panel, $section, $keyword, $main_links, $albums_and_pictures_full_info, $items_amount_per_page) {
        if ($is_admin_panel) {
            return view('adminpages.adminalbum')->with([
                'main_links' => $main_links->mainLinks,
                'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'albumName' => $albums_and_pictures_full_info->album_name,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'nesting_level' => $albums_and_pictures_full_info->albumNestingLevel,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword
                ]);
        } else {
            return view('pages.album')->with([
                'main_links' => $main_links,
                'headTitle' => $albums_and_pictures_full_info->head_title,
                'albumName' => $albums_and_pictures_full_info->album_name,           
                'albums_and_pictures' => $albums_and_pictures_full_info->albumsAndPictures,
                'parents' => $albums_and_pictures_full_info->albumParents,
                'pagination_info' => $albums_and_pictures_full_info->paginator_info,
                'total_number_of_items' => $albums_and_pictures_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section
                ]);                   
        }
    }
    
    private function getAlbum($keyword, $page, $items_amount_per_page, $including_invisible){
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $album = \App\Album::where('keyword', $keyword)->firstOrFail();
        
        $nesting_level = \App\AlbumData::where('items_id', $album->id)->select('nesting_level')->firstOrFail();
            
        //Here we are calling method which will merge all pictures and folders from selected folder into one array
        if ($including_invisible) {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures(\App\Album::where('included_in_album_with_id', '=', $album->id)->orderBy('created_at','DESC')->get(), \App\Picture::where('album_id', $album->id)->orderBy('created_at','DESC')->get());
        } else {
            $albums_and_pictures_full = $this->get_included_albums_and_pictures(\App\Album::where('included_in_album_with_id', '=', $album->id)->where('is_visible', '=', 1)->orderBy('created_at','DESC')->get(), \App\Picture::where('album_id', $album->id)->orderBy('created_at','DESC')->get());
        }
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator
        
        //We need the object below which will contain an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $albums_and_pictures_full_info = new AlbumAndPictureForViewFullInfoForPage();
        
        $albums_and_pictures_full_info->albumNestingLevel = $nesting_level->nesting_level;
        
        if($album->included_in_album_with_id === NULL) {
            $albums_and_pictures_full_info->albumParents = 0;
        }
        else {
            $albums_and_pictures_full_info->albumParents = array_reverse($this->get_albums_parents_for_view($album->included_in_album_with_id));
        }
        
        $albums_and_pictures_full_info->album_name = $album->keyword;
        $albums_and_pictures_full_info->head_title = $album->album_name;
        $albums_and_pictures_full_info->total_number_of_items = count($albums_and_pictures_full);

        //The following information we can have only if we have at least one item in selected folder
        if(count($albums_and_pictures_full) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $albums_and_pictures_full_cut_into_pages = array_chunk($albums_and_pictures_full, $items_amount_per_page, false);
            $albums_and_pictures_full_info->paginator_info = (new CommonRepository())->get_paginator_info($page, $albums_and_pictures_full_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            if ($albums_and_pictures_full_info->paginator_info->number_of_pages >= $page) {
                //The line below selects the page we need, as computer counts from 0, we need to subtract 1
                $albums_and_pictures_full_info->albumsAndPictures = $albums_and_pictures_full_cut_into_pages[$page-1];
            }           
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showAlbumView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $albums_and_pictures_full_info->paginator_info = new Paginator();
            $albums_and_pictures_full_info->paginator_info->number_of_pages = 1;
        }
               
        return $albums_and_pictures_full_info;
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //album keyword or editing existing.
    public function get_all_albums_keywords() {
        
        $all_albums_keywords = \App\Album::all('keyword');
        
        $albums_keywords_array = array();
        
        foreach ($all_albums_keywords as $album_keyword) {
            array_push($albums_keywords_array, $album_keyword->keyword);
        }
        
        return $albums_keywords_array;
        
    }

    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_albums_and_pictures($included_albums, $pictures){
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $albums_and_pictures_full = array();       
        $included_albums_count = count($included_albums);
        
        for($i = 0; $i < $included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $included_albums[$i]->keyword;
            $albums_and_pictures_full[$i]->caption = $included_albums[$i]->album_name;
            $albums_and_pictures_full[$i]->type = 'album';
            //$albums_and_pictures_full[$i]->fileExtension = 0;   
        }
                        
        for($i = $included_albums_count; $i < count($pictures)+$included_albums_count; $i++) {
            $albums_and_pictures_full[$i] = new AlbumAndPictureForView();
            $albums_and_pictures_full[$i]->keyWord = $pictures[$i-$included_albums_count]->keyword;
            $albums_and_pictures_full[$i]->caption = $pictures[$i-$included_albums_count]->picture_caption;
            $albums_and_pictures_full[$i]->type = 'picture';
            $albums_and_pictures_full[$i]->fileExtension = $pictures[$i-$included_albums_count]->file_extension;
        }
        
        return $albums_and_pictures_full;
    }
    
    private function get_albums_parents_for_view($id) {
        
        $parent_album = \App\Album::where('id', $id)->firstOrFail();
        
        $parent_album_for_view = new AlbumLinkForView();
        
        $parent_album_for_view->keyWord = $parent_album->keyword;
        $parent_album_for_view->name = $parent_album->album_name;
        
        $parent_albums_for_view = array();
        
        $parent_albums_for_view[] = $parent_album_for_view;
        
        if ($parent_album->included_in_album_with_id === NULL) {
            return $parent_albums_for_view;
        }
        else {
            $albums_parents_for_view = $this->get_albums_parents_for_view($parent_album->included_in_album_with_id);
            foreach ($albums_parents_for_view as $albums_parent_for_view) {
                $parent_albums_for_view[] = $albums_parent_for_view;
            }
            return $parent_albums_for_view;
        }
    }
    
}
