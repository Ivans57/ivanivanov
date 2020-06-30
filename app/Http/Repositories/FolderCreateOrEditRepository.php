<?php

namespace App\Http\Repositories;

use App\Http\Repositories\AlbumCreateOrEditRepository;


class FolderCreateOrEditRepository extends AlbumCreateOrEditRepository {
    
    //We need this function to simplify getParents function.
    protected function get_parents_from_query($localization, $page, $directory_to_find, $directory_to_exclude_keyword, $records_to_show) {//+
        
        $items_children = $this->get_directory_children_array($directory_to_exclude_keyword);
        
        $max_acceptable_nest_level = $this->get_max_acceptable_nest_level($directory_to_exclude_keyword);
        
        if ($localization === "en") {
            $parents = \App\Folder::select('en_folders.id', 'en_folders.keyword', 'en_folders.folder_name')
                        ->join('en_folders_data', 'en_folders_data.items_id', '=', 'en_folders.id')
                        ->where('folder_name', 'LIKE', "%$directory_to_find%")
                        ->where('en_folders.keyword', '!=', $directory_to_exclude_keyword)
                        ->whereNotIn('en_folders.id', $items_children)
                        ->where('en_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('en_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parents = \App\Folder::select('ru_folders.id', 'ru_folders.keyword', 'ru_folders.folder_name')
                        ->join('ru_folders_data', 'ru_folders_data.items_id', '=', 'ru_folders.id')
                        ->where('folder_name', 'LIKE', "%$directory_to_find%")
                        ->where('ru_folders.keyword', '!=', $directory_to_exclude_keyword)
                        ->whereNotIn('ru_folders.id', $items_children)
                        ->where('ru_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
            
        return $parents;
    }
    
    //Direct data from query is giving useful additional information for pagination.
    //In some cases need to call this function.
    protected function get_parent_list_from_query($localization, $page, $parent_node_id, 
                                                $keyword_of_directory_to_exclude, $max_acceptable_nest_level, $records_to_show) {//+
        if ($localization === "en") {
            $parent_list_from_query = \App\Folder::select('en_folders.id', 'en_folders.folder_name', 'en_folders_data.children', 
                                                        'en_folders_data.nesting_level')
                                ->join('en_folders_data', 'en_folders_data.items_id', '=', 'en_folders.id')
                                ->where('en_folders.included_in_folder_with_id', $parent_node_id)
                                ->where('en_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                                ->where('en_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('en_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Folder::select('ru_folders.id', 'ru_folders.folder_name', 'ru_folders_data.children', 
                                                        'ru_folders_data.nesting_level')
                                ->join('ru_folders_data', 'ru_folders_data.items_id', '=', 'ru_folders.id')
                                ->where('ru_folders.included_in_folder_with_id', $parent_node_id)
                                ->where('ru_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                                ->where('ru_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                                ->orderBy('ru_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
        
        return $parent_list_from_query;
    }
    
    protected function get_id_of_parent($directory_id) {//+
        $directory = new Directory();
        $parent_id_of_parent = \App\Folder::select('folder_name', 'included_in_folder_with_id')->where('id', $directory_id)->firstOrFail();
        $directory->DirectoryName = $parent_id_of_parent->folder_name;
        $directory->ParentId = $parent_id_of_parent->included_in_folder_with_id;
        return $directory;
    }
    
    //This information is requred to calculate the location of an element to open proper page.
    protected function get_full_parent_list_from_query($localization, $keyword_of_directory_to_exclude, $included_in_directory_with_id, 
                                                    $max_acceptable_nest_level) {//+
        //These requests we need to do only to get a data for pagination, because required record might be not on the first page.
        if ($localization === "en") {
            $parent_list_from_query_for_data = \App\Folder::select('en_folders.id', 'en_folders.folder_name', 'en_folders_data.children', 
                                                                'en_folders_data.nesting_level')
                            ->join('en_folders_data', 'en_folders_data.items_id', '=', 'en_folders.id')
                            ->where('en_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('en_folders.included_in_folder_with_id', $included_in_directory_with_id)
                            ->where('en_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_folders.created_at','DESC')->get();
        } else {
            $parent_list_from_query_for_data = \App\Folder::select('ru_folders.id', 'ru_folders.folder_name', 'ru_folders_data.children', 
                                                                'ru_folders_data.nesting_level')
                            ->join('ru_folders_data', 'ru_folders_data.items_id', '=', 'ru_folders.id')
                            ->where('ru_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('ru_folders.included_in_folder_with_id', $included_in_directory_with_id)
                            ->where('ru_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('ru_folders.created_at','DESC')->get();
        }
        return $parent_list_from_query_for_data;
    }
    
    //We need this function to make smaller function get_parents_and_pagination_info_for_array.
    protected function get_parents_for_opened_list_from_query($localization, $keyword_of_directory_to_exclude, $included_in_directory_with_id, 
                                                            $records_to_show, $max_acceptable_nest_level, $page) {//+
        if ($localization === "en") {   
            $parent_list_from_query = \App\Folder::select('en_folders.id', 'en_folders.folder_name', 'en_folders_data.children', 
                                                        'en_folders_data.nesting_level')
                            ->join('en_folders_data', 'en_folders_data.items_id', '=', 'en_folders.id')
                            ->where('en_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                            ->where('en_folders.included_in_folder_with_id', $included_in_directory_with_id)
                            ->where('en_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                            ->orderBy('en_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        } else {
            $parent_list_from_query = \App\Folder::select('ru_folders.id', 'ru_folders.folder_name', 'ru_folders_data.children', 
                                                    'ru_folders_data.nesting_level')
                        ->join('ru_folders_data', 'ru_folders_data.items_id', '=', 'ru_folders.id')
                        ->where('ru_folders.keyword', '!=', $keyword_of_directory_to_exclude)
                        ->where('ru_folders.included_in_folder_with_id', $included_in_directory_with_id)
                        ->where('ru_folders_data.nesting_level', '<', $max_acceptable_nest_level)
                        ->orderBy('ru_folders.created_at','DESC')->paginate($records_to_show, ['*'], 'page', $page);
        }
        return $parent_list_from_query;
    }
    
    //This function transforms data which have been got from database to a special objects array.
    //Usage of that object is giving more flexibility when sharing the same functions with another classes.
    protected function parent_list_from_query_to_object_array($parent_list_from_query) {//+
        $parent_list = array();         
        foreach ($parent_list_from_query as $parent_data_from_query) {
            $parent_data = new DirectoryData();
            $parent_data->DirectoryId = $parent_data_from_query->id;
            $parent_data->DirectoryName = $parent_data_from_query->folder_name;
            $parent_data->Children = $parent_data_from_query->children;
            $parent_data->NestingLevel = $parent_data_from_query->nesting_level;
            array_push($parent_list, $parent_data);
        }
        return $parent_list;
    }
    
    protected function get_direct_children_from_query($directory_id) {//+
        return \App\Folder::select('id')->where('included_in_folder_with_id', '=', $directory_id)->orderBy('created_at','DESC')->get();
    }
    
    protected function get_directory_id_by_keyword($keyword) {//+
        $directory_id = \App\Folder::select('id')->where('keyword', $keyword)->firstOrFail();
        return $directory_id->id;
    }
    
    protected function get_directory_data($items_id) {//+
        $directory_data = new DirectoryData();
        $directory_data_from_query = \App\FolderData::where('items_id', $items_id)->select('nesting_level', 'children')->firstOrFail();
        $directory_data->NestingLevel = $directory_data_from_query->nesting_level;
        $directory_data->Children = $directory_data_from_query->children;
        return $directory_data;
    }
    
    protected function get_children_nest_levels($items_children) {//+
        return \App\FolderData::whereIn('items_id', array_map('intval', $items_children))->select('nesting_level')->get();
    }
}