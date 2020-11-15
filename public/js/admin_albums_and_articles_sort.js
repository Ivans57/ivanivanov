/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums and Articles sorting.*/

//Sorting by name.
$("#sort_by_name").click(function() {
    var element_with_info = document.querySelector('#sort_by_name');
    
    sort_elements(element_with_info, element_with_info.dataset.sorting_mode, $('input[name="show_only_visible"]').val(), 
                    $("input[name='directories_or_files_first']:checked").val());
});
    
//Sorting by creation date and time.
$("#sort_by_creation").click(function() {
    var element_with_info = document.querySelector('#sort_by_creation');
    
    sort_elements(element_with_info, element_with_info.dataset.sorting_mode, $('input[name="show_only_visible"]').val(), 
                    $("input[name='directories_or_files_first']:checked").val());
});
    
//Sorting by update date and time.
$("#sort_by_update").click(function() {
    var element_with_info = document.querySelector('#sort_by_update');   
    
    sort_elements(element_with_info, element_with_info.dataset.sorting_mode, $('input[name="show_only_visible"]').val(), 
                    $("input[name='directories_or_files_first']:checked").val());
});

//This function is required to show folders(albums) or articles(pictures) first.
$("input[type='radio']").change(function() {
    var element_with_info = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');
    //The lines below are required to assign the variable in case we albums section is being used.
    if (element_with_info === null) {
        element_with_info = document.querySelector('.admin-panel-albums-picture-and-album-header-caret-used');
    }
    
    sort_elements(element_with_info, element_with_info.dataset.current_sorting_mode, $('input[name="show_only_visible"]').val(), $(this).val());
});

//This function is required if we need to display or hide invisible items.
$('input[name="show_only_visible"]').change(function(){
    var element_with_info = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');
    //The lines below are required to assign the variable in case we albums section is being used.
    if (element_with_info === null) {
        element_with_info = document.querySelector('.admin-panel-albums-picture-and-album-header-caret-used');
    }
      
    sort_elements(element_with_info, element_with_info.dataset.current_sorting_mode, (($(this).val() === 'all') ? 'only_visible' : 'all'), 
                    $("input[name='directories_or_files_first']:checked").val());
});

//Sorting_mode variable is a part of element_with_info, but it is required to take it separately, becuase depending on kind of sorting action,
//there might be required to use sorting_mode (what is going to be used) or current sorting mode (to keep it).
function sort_elements(element_with_info, sorting_mode, show_invisible, directories_or_files_first_value=null) {
    //If it is an english localization, we don't need to show it, because it is a default localization.
    var localization = (element_with_info.dataset.localization === "en") ? "" : "/ru";
    var current_sorting_method = document.querySelector('#'+element_with_info.id);
    var url;
    if (element_with_info.dataset.is_level_zero === "1") {
        url = localization+"/admin/"+element_with_info.dataset.section+"/"+show_invisible+"/"+current_sorting_method.id+"_"+sorting_mode;
    } else {
        //Some directories are single entity.
        if (directories_or_files_first_value === null) {
            url = localization+"/admin/"+element_with_info.dataset.section+"/"+element_with_info.dataset.parent_keyword+
                        "/page/1/"+show_invisible+"/"+current_sorting_method.id+"_"+sorting_mode;
        } else {
            url = localization+"/admin/"+element_with_info.dataset.section+"/"+element_with_info.dataset.parent_keyword+
                        "/page/1/"+show_invisible+"/"+current_sorting_method.id+"_"+sorting_mode+"/"+directories_or_files_first_value;
        }
    }
    window.location.href = url;
}

/*--------------------------------------------------------*/