/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums and Articles sorting.*/

$( document ).ready(function() {
    //We need the following lines to make ajax requests work.
    //There are special tokens used for security. We need to add them in all heads
    //and also ajax should be set up to pass them.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //Sorting by name, creation date and time, update date and time.
    //There will be two sort modes normal and for search.
    $(document).on("click", ".sort", function() {
        if ($(this).data('search_is_on') === 0) {           
            sort_elements($("#show_only_visible")[0], $(this).attr('id')+"_"+$(this).data('sorting_mode'), $('input[name="show_only_visible"]').val(), 
                          get_directories_or_files_first($("#show_only_visible").data('old_directories_or_files_first')));
        } else {
            folder_search($("#search_is_on").val(), make_search_url(), $("#folder_search").val(), $("input[name='what_to_search']:checked").val(), 
                          $("#show_only_visible").val(), 1, $(this).attr('id'), $(this).data('sorting_mode'));
        }
    });

    //This function is required to show folders(albums) or articles(pictures) first.
    $('input[name="directories_or_files_first"]').change(function() {
        var element_with_info = document.querySelector('#show_only_visible');

        sort_elements(element_with_info, element_with_info.dataset.old_sorting_method_and_mode, $('input[name="show_only_visible"]').val(), 
                      $(this).val());
    });

    //This function is required if we need to display or hide invisible items.
    $(document).on("click", "#show_only_visible", function() {
        if($("#search_is_on").val() === '0') {
            var element_with_info = document.querySelector('#show_only_visible');    
            var directories_or_files_first = get_directories_or_files_first(element_with_info.dataset.old_directories_or_files_first);

            sort_elements(element_with_info, element_with_info.dataset.old_sorting_method_and_mode,(($(this).val() === 'all') ? 'only_visible' : 'all'), 
                          directories_or_files_first);
        } else {
            var current_sorting_method_element = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');
            if (current_sorting_method_element === null) {
                folder_search($("#search_is_on").val(), make_search_url(), $("#folder_search").val(), $("input[name='what_to_search']:checked").val(), (($(this).val() === 'all') ? 'only_visible' : 'all'), 1);
            } else {
                folder_search($("#search_is_on").val(), make_search_url(), $("#folder_search").val(), $("input[name='what_to_search']:checked").val(), (($(this).val() === 'all') ? 'only_visible' : 'all'), 1, 
                                current_sorting_method_element.id, (current_sorting_method_element.dataset.sorting_mode === "desc") ? "asc" : "desc");
            }
        }
    });

    //This function is required to remove extra code from sorting functions.
    function get_directories_or_files_first(old_directories_or_files_first) {
        var directories_or_files_first = $("input[name='directories_or_files_first']:checked").val();
        if (typeof directories_or_files_first === 'undefined') {
            //The line below is required to keep old_directories_or_files_first setting in case this elements disappear 
            //when hiding invisible items.
            directories_or_files_first = old_directories_or_files_first;
        }    
        return directories_or_files_first;
    }

    //element_with_sorting_info_id and element_with_sorting_info_sorting_mode are taken from the same element, 
    //but it is required to take them as separate variables (end then merge), because depending on kind of sorting action,
    //there might be required to use sorting_mode (what is going to be used) or current sorting mode (to keep it).
    function sort_elements(element_with_info, sorting_info_id_and_sorting_mode, show_invisible, directories_or_files_first_value=null) {
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var localization = (element_with_info.dataset.localization === "en") ? "" : "/ru";
        var url;
        
        if (element_with_info.dataset.is_level_zero === "1") {
            //It is enough to check just element_with_sorting_info_id, because if element_with_sorting_info_id is null,
            //then element_with_sorting_info_id_sorting_mode will be null as well.
            url = (sorting_info_id_and_sorting_mode !== null) ? (localization+"/admin/"+element_with_info.dataset.section+"/"+show_invisible+"/"+
                   sorting_info_id_and_sorting_mode) : (localization+"/admin/"+element_with_info.dataset.section+"/"+show_invisible+"/"+
                   element_with_info.dataset.old_sorting_method_and_mode);
        } else {
            //Some directories are single entity.
            if (directories_or_files_first_value === null) {
                url = (sorting_info_id_and_sorting_mode !== null) ? (localization+"/admin/"+element_with_info.dataset.section+"/"+
                       element_with_info.dataset.parent_keyword+"/page/1/"+show_invisible+"/"+sorting_info_id_and_sorting_mode) : 
                      (localization+"/admin/"+element_with_info.dataset.section+"/"+element_with_info.dataset.parent_keyword+"/page/1/"+
                       show_invisible+"/"+element_with_info.dataset.old_sorting_method_and_mode);
            } else {
                url = (sorting_info_id_and_sorting_mode !== null) ? (localization+"/admin/"+element_with_info.dataset.section+"/"+
                       element_with_info.dataset.parent_keyword+"/page/1/"+show_invisible+"/"+sorting_info_id_and_sorting_mode+"/"+
                       directories_or_files_first_value) : (localization+"/admin/"+element_with_info.dataset.section+"/"+
                       element_with_info.dataset.parent_keyword+"/page/1/"+show_invisible+"/"+
                       element_with_info.dataset.old_sorting_method_and_mode+"/"+directories_or_files_first_value);
            }
        }
        window.location.href = url;
    }
    
    //++++++++++++++++++++++Folder Search.++++++++++++++++++++++
    //This function is required for localization application.
    function make_search_url() {
        var url_for_search_without_localization = "/admin/articles/search";
        var localization = $("#folder_search_button").data('localization');
        var url_for_search = (localization === 'en') ? url_for_search_without_localization : "/"+localization+url_for_search_without_localization;
        
        return url_for_search;
    }
    
    $( "#folder_search_button" ).click(function() {
        folder_search($("#search_is_on").val(), make_search_url(), $("#folder_search").val(), $("input[name='what_to_search']:checked").val(), $("#show_only_visible").val(), 1);
    });   
    
    //This event needs to be done like below ($(document).on("click", ...), because due to ajax usage it can't be done like a normal event.
    $(document).on("click", ".turn-page", function() {
        var current_sorting_method_element = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');      
        var go_to_page_number = $(this).data('page');
        
        if (($(this).attr('id')) === "previous_page") {
            go_to_page_number = go_to_page_number - 1;
        } else if (($(this).attr('id')) === "next_page") {
            go_to_page_number = go_to_page_number + 1;
        }
        
        //The first parameter will always be "1", because pagination arrows for search will appear only when search mode is on.
        folder_search("1", make_search_url(), $("#folder_search").val(), $("input[name='what_to_search']:checked").val(), $("#show_only_visible").val(), go_to_page_number, current_sorting_method_element.id, 
                       (current_sorting_method_element.dataset.sorting_mode === "desc") ? "asc" : "desc");
    });
    
    //The function below is calling search function.
    //The last two parameters we have to pass separately, because depending on whether a user is going two swicth within
    //different sorting modes or turn the pages, needs to be applied current sorting mode (asc or desc) or opposite one.
    function folder_search(search_is_on, url, find_folders_by_name, what_to_search, show_only_visible, page_number, sorting_method = null, sorting_mode = null) {
        if (sorting_method === null || sorting_mode === null) {
            var sorting_method_and_mode = null;
        } else {
            var sorting_method_and_mode = sorting_method+"_"+sorting_mode;
        }
        $.ajax({
                type: "POST",
                url: url,
                data: {search_is_on: search_is_on, find_folders_by_name: find_folders_by_name, page_number: page_number, 
                       what_to_search: what_to_search, sorting_mode: sorting_method_and_mode, show_only_visible: show_only_visible},
                success:function(data) {
                    if ($(".admin-panel-articles-title").length) {
                        $('.admin-panel-articles-title').html(data.title);
                    }
                    $('.admin-panel-articles-control-buttons').html(data.control_buttons);
                    $('.admin-panel-articles-content').html(data.content);
                }
            });
    }
    
    //++++++++++++++++++++++End of Folder Search.++++++++++++++++++++++
});
/*--------------------------------------------------------*/