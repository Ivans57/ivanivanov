<?php

namespace App\Http\Repositories;

class DirectoryParentsData {
    public $parentsDataArray;
    public $paginationInfo;
}

class PaginationInfoForParentSearch {
    public $previousPage;
    public $nextPage;
}

class DirectoryBasic {
    public $DirectoryId;
    public $DirectoryName;
}

class Directory extends DirectoryBasic {
    public $ParentId;
    public $DirectoryKeyword;
}

class ParentDropDownListElement extends DirectoryBasic {
    public $HasChildren;
    //The last two properties are used only when making opened list to indicate 
    //whether a line is closed or open to show a caret and its events in 
    //a proper state and mark selected record.
    public $isOpened = false;
    public $inFocus = false;
}

class DirectoryData extends DirectoryBasic {
    public $NestingLevel;
    public $Children;
}

class AlbumParentsRepository {
    //We need this function for Directory Parent search field when create or edit directory.
    public function getParents($localization, $page, $directory_to_find, $directory_to_exclude_keyword, $mode) {
              
        $parents = new DirectoryParentsData();
        
        $records_to_show = 10;    
        $parents_from_query = $this->get_parents_from_query($localization, $mode, $page, $directory_to_find, 
                                                            //As need to show all directories when creating or editing in a file mode,
                                                            //then need to exclude $keyword_of_directory_to_exclude variable.
                                                            $mode == "file" ? $directory_to_exclude_keyword = null : $directory_to_exclude_keyword, 
                                                            $records_to_show);
        $parents->paginationInfo = $this->get_pagination_info($parents_from_query);
        $parents->parentsDataArray = array();
        
        if ($directory_to_find && count($parents_from_query) > 0) {
            
            foreach ($parents_from_query as $directory) {
                $directory_path = $this->get_full_directory_path($directory->id, "", "name");
                $parent_data_array = [$directory->id, $directory_path];
                array_push($parents->parentsDataArray, $parent_data_array);
            }
        }          
        return $parents;
    }
    
    //We need this function for Directory Parent search field when create or edit directory.
    //It is not enough to get just a name of the directory, we need to get a full path to show.
    //We need the third argument, because we are using the same function for parent search and also for albums creation in a file system.
    //There will be small difference and to meet it we need the third argument $name_or_keyword_path.
    //As we are going to use this method out of this repository, it has to be public.
    public function get_full_directory_path($directory_id, $directory_path, $name_or_keyword_path) {
        //We cannot get information from data table, because in this case the sequence of items is important.
        $directory = $this->get_id_of_parent($directory_id);
        if ($name_or_keyword_path == "name") {
            $directory_full_path = substr_replace($directory_path, ' / '.$directory->DirectoryName, 0, 0);
        } else {
            $directory_full_path = substr_replace($directory_path, '/'.$directory->DirectoryKeyword, 0, 0);
        }
        if ($directory->ParentId != 0) {
            $directory_full_path = $this->get_full_directory_path($directory->ParentId, $directory_full_path, $name_or_keyword_path);
        }
        return $directory_full_path;
    }
    
    //We need this function to simplify getParents function.
    protected function get_parents_from_query($localization, $mode, $page, $directory_to_find, $directory_to_exclude_keyword, $records_to_show) {
        
        $items_children = $this->get_directory_children_array($directory_to_exclude_keyword);
        
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($mode, $directory_to_exclude_keyword);
        
        if ($localization === "en") {
            $parents = \App\Album::select('en_albums.id', 'en_albums.keyword', 'en_albums.album_name')
                        ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                        ->where('album_name', 'LIKE', "%$directory_to_find%")
                        ->where('en_albums.keyword', '!=', $directory_to_exclude_keyword)
                        ->whereNotIn('en_albums.id', $items_children)
                        ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parents = \App\Album::select('ru_albums.id', 'ru_albums.keyword', 'ru_albums.album_name')
                        ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                        ->where('album_name', 'LIKE', "%$directory_to_find%")
                        ->where('ru_albums.keyword', '!=', $directory_to_exclude_keyword)
                        ->whereNotIn('ru_albums.id', $items_children)
                        ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
            
        return $parents;
    }
    
    //We need this function to simplify getParents and get_parents_from_query functions.
    protected function get_directory_children_array($directory_to_exclude_keyword) {
        if($directory_to_exclude_keyword) {     
            //We will do two request to avoid localization check.
            $directory_id = $this->get_directory_id_by_keyword($directory_to_exclude_keyword);

            $items_children_from_query = $this->get_directory_data($directory_id);
            
            $items_children_array = json_decode($items_children_from_query->Children, true);
            
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
    
    //We need this function for Directory Parent dropdown list when create or edit directory.
    public function getParentList($localization, $page, $parent_id, $parent_node_id, $keyword_of_directory_to_exclude, $mode) {
        
        $parents = new DirectoryParentsData();        
        $records_to_show = 10;
  
        //parent_id is an indicator whether a directory is located on 0 nestenig level or no.
        //0 nesting level directories are easier to process.
        if ($parent_id == 0 || $parent_node_id !== null) {
            //Getting closed parent list only when creating or editing an album in root album.
            $parents = $this->get_closed_parent_list($localization, $mode, $page, $records_to_show, 
                                                    ($parent_node_id == 0 ? $parent_node_id = null : $parent_node_id),
                                                    //As need to show all directories when creating or editing in a file mode,
                                                    //then need to exclude $keyword_of_directory_to_exclude variable.
                                                    $mode == "file" ? $keyword_of_directory_to_exclude = null : $keyword_of_directory_to_exclude);
            
        } else {
            //Getting opened parent list only when creating or editing a directory 
            //in any directory except of the root directory.
            $parents = $this->get_opened_parent_list($localization, $mode, $records_to_show, $parent_id,
                                                    //As need to show all directories when creating or editing in a file mode,
                                                    //then need to exclude $keyword_of_directory_to_exclude variable.
                                                    $mode == "file" ? $keyword_of_directory_to_exclude = null : $keyword_of_directory_to_exclude);
        }       
        return $parents;
    }
    
    //This function will be called when user is creating a new directory on level 0,
    //or when is editing a directory which is located on level 0.
    private function get_closed_parent_list($localization, $mode, $page, $records_to_show, $parent_node_id, $keyword_of_directory_to_exclude) {
        $parents = new DirectoryParentsData();
        
        //We need this for additional check whether a being checked item has children.
        $id_of_directory_to_exclude = null;
        if ($keyword_of_directory_to_exclude) {
            $id_of_directory_to_exclude = $this->get_directory_id_by_keyword($keyword_of_directory_to_exclude);
        }
        //Below we need to get some information which will help to filter albums with unacceptable nesting level
        //as nesting levels amount is limited.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($mode, $keyword_of_directory_to_exclude);
        
        $parent_list_from_query = $this->get_parent_list_from_query($localization, $page, $parent_node_id,
                                                                                $keyword_of_directory_to_exclude, 
                                                                                $max_acceptable_nest_level, $records_to_show);
        
        $parent_list_array = array();

        if (count($parent_list_from_query) > 0) {
            $parent_list_array = $this->make_parent_object_array_for_parent_list($parent_list_from_query, $max_acceptable_nest_level, 
                                                                                $id_of_directory_to_exclude);
        }
        $parents->parentsDataArray = $parent_list_array;

        $parents->paginationInfo = $this->get_pagination_info($parent_list_from_query);
        
        return $parents;
    }
    
    //Direct data from query is giving useful additional information for pagination.
    //In some cases need to call this function.
    protected function get_parent_list_from_query($localization, $page, $parent_node_id, 
                                                $keyword_of_directory_to_exclude, $max_acceptable_nest_level, $records_to_show) {
        if ($localization === "en") {
            $parent_list_from_query = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                        'en_albums_data.nesting_level')
                                ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                                ->where('en_albums.included_in_album_with_id', $parent_node_id)
                                ->where('en_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                                ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                        'ru_albums_data.nesting_level')
                                ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                                ->where('ru_albums.included_in_album_with_id', $parent_node_id)
                                ->where('ru_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                                ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
        
        return $parent_list_from_query;
    }
    
    //This function will be called when user is creating a new directory on level 0,
    //or when is editing a directory which is located on level 0.
    private function get_opened_parent_list($localization, $mode, $records_to_show, $parent_id, $keyword_of_directory_to_exclude) {
        $parents = new DirectoryParentsData();
        //First of all need to make an array of all ancestors of the item.
        //There is a special field for them in data table, but their sequence might be wrong.
        $parent_id_array = array();
        $parent_id_obj = new Directory();
        $parent_id_obj->ParentId = intval($parent_id);
        array_push($parent_id_array, $parent_id_obj);
        $parent_ids = $this->get_all_parents_ids_for_item($parent_id_obj->ParentId, $parent_id_array);

        //Comparing to create option, each of these properties will have an array, as
        //on client's side javascript will take each array and form a list of items from it
        //on some certain nesting level, then will take elements of the next nesting level.
        $parentsDataArrayReversed = array();
        $parentsPaginationInfoReversed = array();
        
        for ($i = 0; $i < count($parent_ids); $i++) {
            $parents_and_pagination_info_for_array = $this->get_parents_and_pagination_info_for_array($localization, $mode, 
                                                                                                    $parent_ids[$i]->ParentId, 
                                                                                                    $records_to_show, $i,
                                                                                                    $keyword_of_directory_to_exclude);
            array_push($parentsDataArrayReversed, $parents_and_pagination_info_for_array->parentsDataArray);
            array_push($parentsPaginationInfoReversed, $parents_and_pagination_info_for_array->paginationInfo);
        }
        
        $parents->parentsDataArray = array_reverse($parentsDataArrayReversed);
        $parents->paginationInfo = array_reverse($parentsPaginationInfoReversed);
        
        return $parents;
    }
    
    //This function extracts all ancestors of the selected record.
    private function get_all_parents_ids_for_item($item_id, $parent_ids) {
        $parent_id_new = $this->get_id_of_parent($item_id);
        if ($parent_id_new->ParentId !== null) {
            array_push($parent_ids, $parent_id_new);
            $parent_ids = $this->get_all_parents_ids_for_item($parent_id_new->ParentId, $parent_ids);
        }
        return $parent_ids;
    }
    
    //This function will get parent information and pagination information for
    //parents and their pagination information array, which will be required for
    //parent dropdown list for Directory edit window.
    private function get_parents_and_pagination_info_for_array($localization, $mode, $parent_id, $records_to_show, $iteration, 
                                                            $keyword_of_directory_to_exclude) {
        $parents_for_array = new DirectoryParentsData();
        $parent_id_of_parent = $this->get_id_of_parent($parent_id);
        
        //Below we need to get some information which will help to filter directories with unacceptable nesting level
        //as nesting levels amount is limited.
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($mode, $keyword_of_directory_to_exclude);
        
        //We need an id of directory which we need to exclude from parents list to find all its direct children.
        $id_of_directory_to_exclude = null;
        if ($keyword_of_directory_to_exclude) {
            $id_of_directory_to_exclude = $this->get_directory_id_by_keyword($keyword_of_directory_to_exclude);
        }
        //First need to find out record's location in parents array as it might be not on the first page.
        $record_location = $this->get_record_location_in_parent_list($localization, $parent_id, $keyword_of_directory_to_exclude, 
                                                                            $parent_id_of_parent->ParentId, 
                                                                            $max_acceptable_nest_level);
        
        //ceil function chooses the next bigger value of a decimal fraction, e.g. 5.1 => 6
        //intval converts float which we get from ceil to int.
        $page = intval(ceil($record_location/$records_to_show));
        $parent_list_from_query = $this->get_parents_for_opened_list_from_query($localization, $keyword_of_directory_to_exclude, 
                                                                                $parent_id_of_parent->ParentId, 
                                                                                $records_to_show, $max_acceptable_nest_level, $page);
   
        $parents_for_array->parentsDataArray = $this->make_parent_object_array_for_parent_list($parent_list_from_query, 
                                               $max_acceptable_nest_level, $id_of_directory_to_exclude, $parent_id, $iteration);          
        $parents_for_array->paginationInfo = $this->get_pagination_info($parent_list_from_query);
        
        return $parents_for_array;
    }
    
    protected function get_id_of_parent($directory_id) {
        $directory = new Directory();
        $parent_id_of_parent = \App\Album::select('keyword', 'album_name', 'included_in_album_with_id')->where('id', $directory_id)->firstOrFail();
        $directory->DirectoryKeyword = $parent_id_of_parent->keyword;
        $directory->DirectoryName = $parent_id_of_parent->album_name;
        $directory->ParentId = $parent_id_of_parent->included_in_album_with_id;
        return $directory;
    }
    
    //We need this function to make smaller function get_parents_and_pagination_info_for_array.
    private function get_record_location_in_parent_list($localization, $parent_id, $keyword_of_directory_to_exclude, 
                                                        $included_in_directory_with_id, $max_acceptable_nest_level) {
        
        //This request we need to do only to get a data for pagination, because required record might be not on the first page.
        $parent_list_from_query_for_data = $this->get_full_parent_list_from_query($localization, $keyword_of_directory_to_exclude, 
                                                                                  $included_in_directory_with_id, 
                                                                                  $max_acceptable_nest_level);
                 
        $items_amount = count($parent_list_from_query_for_data);      
        $record_location = 0;
        
        for ($i = 0; $i <= $items_amount; $i++) {
            if ($parent_list_from_query_for_data[$i]->id == $parent_id) {
                $record_location = $i + 1;
                $i = $items_amount;
            }
        }
        return $record_location;
    }
    
    //This information is requred to calculate the location of an element to open proper page.
    protected function get_full_parent_list_from_query($localization, $keyword_of_directory_to_exclude, $included_in_directory_with_id, 
                                                    $max_acceptable_nest_level) {//+
        //These requests we need to do only to get a data for pagination, because required record might be not on the first page.
        if ($localization === "en") {
            $parent_list_from_query_for_data = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                                'en_albums_data.nesting_level')
                            ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                            ->where('en_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('en_albums.included_in_album_with_id', $included_in_directory_with_id)
                            ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_albums.created_at','DESC')->get();
        } else {
            $parent_list_from_query_for_data = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                                'ru_albums_data.nesting_level')
                            ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                            ->where('ru_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('ru_albums.included_in_album_with_id', $included_in_directory_with_id)
                            ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('ru_albums.created_at','DESC')->get();
        }
        return $parent_list_from_query_for_data;
    }
    
    //We need this function to make smaller function get_parents_and_pagination_info_for_array.
    protected function get_parents_for_opened_list_from_query($localization, $keyword_of_directory_to_exclude, $included_in_directory_with_id, 
                                                            $records_to_show, $max_acceptable_nest_level, $page) {
        if ($localization === "en") {   
            $parent_list_from_query = \App\Album::select('en_albums.id', 'en_albums.album_name', 'en_albums_data.children', 
                                                        'en_albums_data.nesting_level')
                            ->join('en_albums_data', 'en_albums_data.items_id', '=', 'en_albums.id')
                            ->where('en_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('en_albums.included_in_album_with_id', $included_in_directory_with_id)
                            ->where('en_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Album::select('ru_albums.id', 'ru_albums.album_name', 'ru_albums_data.children', 
                                                    'ru_albums_data.nesting_level')
                        ->join('ru_albums_data', 'ru_albums_data.items_id', '=', 'ru_albums.id')
                        ->where('ru_albums.keyword', '!=', $keyword_of_directory_to_exclude)
                        ->where('ru_albums.included_in_album_with_id', $included_in_directory_with_id)
                        ->where('ru_albums_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_albums.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
        return $parent_list_from_query;
    }
           
    //We need this function to make smaller functions get_parents_and_pagination_info_for_array and get_closed_parent_list.
    private function make_parent_object_array_for_parent_list($parent_list_from_query, $max_acceptable_nest_level, 
                                                                    $id_of_directory_to_exclude, $parent_id = null, $iteration = null) {//-
        
        $parent_list_from_query_objects = $this->parent_list_from_query_to_object_array($parent_list_from_query);
        
        $parent_list_array = array();
        
        foreach ($parent_list_from_query_objects as $directory) {
            $parent_data_array = new ParentDropDownListElement();
            $parent_data_array->DirectoryId = $directory->DirectoryId;
            $parent_data_array->DirectoryName = $directory->DirectoryName;
            
            //We need to use this function as due to some filters we might need 
            //to exclude some children from item's children list.
            $parent_data_array->HasChildren = true;
            if ($directory->Children === null || $directory->NestingLevel === ($max_acceptable_nest_level - 1)) {
                $parent_data_array->HasChildren = false;
            } else if ($id_of_directory_to_exclude) {
                $parent_data_array->HasChildren = $this->check_for_children($directory->DirectoryId, $id_of_directory_to_exclude);
            }
            //We can use $parent_id as an indicator to know whether a closed or opened list is being build.
            if ($parent_id) {
                //When javascript is building a dropdown list. First it is making list of elements from nesting level 0,
                //it is one iteration, then list from the next nesting level. That will be the second iteration and so on.
                $parent_data = $this->check_if_open_or_in_focus($directory->DirectoryId, $parent_id, $iteration);
                $parent_data_array->inFocus = $parent_data->inFocus;
                $parent_data_array->isOpened = $parent_data->isOpened;
            }           
            array_push($parent_list_array, $parent_data_array);
        }
        return $parent_list_array;
    }
    
    //This function transforms data which have been got from database to a special objects array.
    //Usage of that object is giving more flexibility when sharing the same functions with another classes.
    protected function parent_list_from_query_to_object_array($parent_list_from_query) {
        $parent_list = array();         
        foreach ($parent_list_from_query as $parent_data_from_query) {
            $parent_data = new DirectoryData();
            $parent_data->DirectoryId = $parent_data_from_query->id;
            $parent_data->DirectoryName = $parent_data_from_query->album_name;
            $parent_data->Children = $parent_data_from_query->children;
            $parent_data->NestingLevel = $parent_data_from_query->nesting_level;
            array_push($parent_list, $parent_data);
        }
        return $parent_list;
    }
    
    //This function's purpose is to shorten make_parent_object_array_for_parent_list().
    private function check_if_open_or_in_focus($directory_id, $parent_id, $iteration) {
        $parent_data = new ParentDropDownListElement();
        
        if ($directory_id == $parent_id && $iteration == 0) {
            $parent_data->inFocus = true;
        } else if ($directory_id == $parent_id && $iteration > 0) {
            $parent_data->isOpened = true;
        }
        return $parent_data;
    }
    
      
    //We need to use this function as due to some filters we might need 
    //to exclude some children from item's children list.
    private function check_for_children($directory_id, $id_of_directory_to_exclude) {

        $items_direct_children = $this->get_direct_children_from_query($directory_id);
        
        $hasChildren = false;       
        if (count($items_direct_children) > 1) {
            $hasChildren = true;
        } else {
            if ($items_direct_children[0]->id !== $id_of_directory_to_exclude) {
                $hasChildren = true;
            }
        }      
        return $hasChildren;
    }
    
    protected function get_direct_children_from_query($directory_id) {
        return \App\Album::select('id')->where('included_in_album_with_id', '=', $directory_id)->orderBy('created_at','DESC')->get();
    }
    
    //This function gets a pagination information for Parent search when create or edit directory.
    //We cannot display all directories, as there might be too many of them, which will make the system slow.
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
    
    //We need this function to shorten getParents function.
    protected function get_max_acceptable_nest_level($mode, $directory_to_exclude_keyword = NULL) {
                     
        $max_acceptable_nest_level = ($mode == "directory") ? 7 : 8;
        
        if ($directory_to_exclude_keyword != NULL && $mode == "directory") {
            $items_id = $this->get_directory_id_by_keyword($directory_to_exclude_keyword);
            
            $items_nesting_level_and_children = $this->get_directory_data($items_id);
            
            $items_children = json_decode($items_nesting_level_and_children->Children, true);
            
            if (is_null($items_children)){
                $children_max_nest_level = $items_nesting_level_and_children->NestingLevel;
            } else {
                $children_max_nest_level = $this->get_children_max_nest_level($items_children, $max_acceptable_nest_level);
            }          
            $children_rel_max_nest_level = $children_max_nest_level - $items_nesting_level_and_children->NestingLevel;
            $max_acceptable_nest_level = $max_acceptable_nest_level - $children_rel_max_nest_level;
        }
                   
        return $max_acceptable_nest_level;
    }
    
    protected function get_directory_id_by_keyword($keyword) {
        $directory_id = \App\Album::select('id')->where('keyword', $keyword)->firstOrFail();
        return $directory_id->id;
    }
    
    protected function get_directory_data($items_id) {
        $directory_data = new DirectoryData();
        $directory_data_from_query = \App\AlbumData::where('items_id', $items_id)->select('nesting_level', 'children')->firstOrFail();
        $directory_data->NestingLevel = $directory_data_from_query->nesting_level;
        $directory_data->Children = $directory_data_from_query->children;
        return $directory_data;
    }
    
    //We need this function to shorten get_max_acceptable_nest_level function.
    private function get_children_max_nest_level($items_children, $max_acceptable_nest_level) {
               
        //array_map converts string array to int array.
        $children_nest_levels = $this->get_children_nest_levels(array_map('intval', $items_children));
                
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
    
    protected function get_children_nest_levels($items_children) {
        return \App\AlbumData::whereIn('items_id', array_map('intval', $items_children))->select('nesting_level')->get();
    }
}

