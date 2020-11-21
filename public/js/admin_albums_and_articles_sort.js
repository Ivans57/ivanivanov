/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums and Articles sorting.*/

//Sorting by name.
$("#sort_by_name").click(function() {
    var element_with_info = document.querySelector('#show_only_visible');
    var element_with_sorting_info = document.querySelector('#sort_by_name');
    
    sort_elements(element_with_info, element_with_sorting_info.id+"_"+element_with_sorting_info.dataset.sorting_mode, 
                  $('input[name="show_only_visible"]').val(), $("input[name='directories_or_files_first']:checked").val());
});
    
//Sorting by creation date and time.
$("#sort_by_creation").click(function() {
    var element_with_info = document.querySelector('#show_only_visible');
    var element_with_sorting_info = document.querySelector('#sort_by_creation');
    
    sort_elements(element_with_info, element_with_sorting_info.id+"_"+element_with_sorting_info.dataset.sorting_mode, 
                  $('input[name="show_only_visible"]').val(), $("input[name='directories_or_files_first']:checked").val());
});
    
//Sorting by update date and time.
$("#sort_by_update").click(function() {
    var element_with_info = document.querySelector('#show_only_visible');
    var element_with_sorting_info = document.querySelector('#sort_by_update');   
    
    sort_elements(element_with_info, element_with_sorting_info.id+"_"+element_with_sorting_info.dataset.sorting_mode, 
                  $('input[name="show_only_visible"]').val(), $("input[name='directories_or_files_first']:checked").val());
});

//This function is required to show folders(albums) or articles(pictures) first.
$("input[type='radio']").change(function() {
    var element_with_info = document.querySelector('#show_only_visible');
    
    sort_elements(element_with_info, element_with_info.dataset.old_sorting_method_and_mode, $('input[name="show_only_visible"]').val(), 
                  $(this).val());
});

//This function is required if we need to display or hide invisible items.
$('input[name="show_only_visible"]').change(function() {
    var element_with_info = document.querySelector('#show_only_visible');
    
    var directories_or_files_first = $("input[name='directories_or_files_first']:checked").val();
    if (typeof directories_or_files_first === 'undefined') {
        //The line below is required to keep old_albums_or_pictures_first setting in case this elements disappear 
        //when hiding invisible items.
        directories_or_files_first = element_with_info.dataset.old_directories_or_files_first;
    }
    
    sort_elements(element_with_info, element_with_info.dataset.old_sorting_method_and_mode,(($(this).val() === 'all') ? 'only_visible' : 'all'), 
                  directories_or_files_first);
});

//element_with_sorting_info_id and element_with_sorting_info_sorting_mode are taken from the same element, 
//but it is required to take them as separate variables (end then merge), because depending on kind of sorting action,
//there might be required to use sorting_mode (what is going to be used) or current sorting mode (to keep it).
function sort_elements(element_with_info, sorting_info_id_and_sorting_mode, show_invisible, directories_or_files_first_value=null) {
    //If it is an english localization, we don't need to show it, because it is a default localization.
    var localization = (element_with_info.dataset.localization === "en") ? "" : "/ru";
    //var current_sorting_method = document.querySelector('#'+element_with_sorting_info.id);
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

/*--------------------------------------------------------*/