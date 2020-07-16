<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\AlbumParentsRepository;
use App\Http\Repositories\FolderParentsRepository;

//This controller is required for parent search in database or to give possible parents dropdown list to select a parent for albums or folders.
class AdminParentsController extends Controller
{
    public function findParents(Request $request) {
        
        if ($request->input('section') == "albums") {
            $create_or_edit_window = new AlbumParentsRepository();
        } else {
            $create_or_edit_window = new FolderParentsRepository();
        }
        
        $parents = $create_or_edit_window->getParents($request->input('localization'), $request->input('page'), 
                $request->input('parent_search'), $request->input('keyword'));
                   
        if (count($parents->parentsDataArray) > 0) {              
            return response()->json(['directories_data' => $parents->parentsDataArray, 'pagination_info' => $parents->paginationInfo]);
        } else {
            //Here we need to override the following property, because in case user doesn't enter anything in search
            //system supposed to return nothing instead of everything and there shouldn't be any pages.
            $parents->paginationInfo->nextPage = null;
            return response()->json(['directories_data' => [["0", __('keywords.NothingFound')]], 'pagination_info' => $parents->paginationInfo]);
        }
    }
    
    public function getParentList(Request $request) {
        
        if ($request->input('section') == "albums") {
            $create_or_edit_window = new AlbumParentsRepository();
        } else {
            $create_or_edit_window = new FolderParentsRepository();
        }
        
        //parent_id is an id of parent of the item being edited or when user wants to create a new album in already existing album.
        //parent_node_id is an id of album whcih is getting opened id parent dropdown list to get its nested albums.
        $parents = $create_or_edit_window->getParentList($request->input('localization'), $request->input('page'), $request->input('parent_id'), 
                                $request->input('parent_node_id'), $request->input('keyword_of_directory_to_exclude'));
             
        return response()->json(['parent_list_data' => $parents->parentsDataArray, 'pagination_info' => $parents->paginationInfo]);
    }
}
