/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums and Articles sorting.*/

//Sorting by name.
$( "#sort_by_name" ).click(function() {
    var current_element = document.querySelector('#sort_by_name');
    //The last parameter have only included items.
    albums_or_articles_sort(current_element.id, current_element, $("input[name='directories_or_files_first']:checked").val(), 
                            $('input[name="show_only_visible"]').val());
});
    
//Sorting by creation date and time.
$( "#sort_by_creation" ).click(function() {
    var current_element = document.querySelector('#sort_by_creation');
    //The last parameter have only included items.
    albums_or_articles_sort(current_element.id, current_element, $("input[name='directories_or_files_first']:checked").val(), 
                            $('input[name="show_only_visible"]').val());
});
    
//Sorting by update date and time.
$( "#sort_by_update" ).click(function() {
    var current_element = document.querySelector('#sort_by_update');   
    //The last parameter have only included items.
    albums_or_articles_sort(current_element.id, current_element, $("input[name='directories_or_files_first']:checked").val(), 
                            $('input[name="show_only_visible"]').val());
});
    
//The function below is making a link to do sorting and going to it.
//For elements on level 0 and for different level elements will be different links, for that reason we need that parameter.
//The last parameter have only included items.
function albums_or_articles_sort(sorting_method, current_element, directories_or_files_first_value, show_invisible) {
    //If it is an english localization, we don't need to show it, because it is a default localization.
    var localization = (current_element.dataset.localization === "en") ? "" : "/ru";
    var current_sorting_method = document.querySelector('#'+sorting_method);
    if (current_element.dataset.is_level_zero === "1") {
        var url = localization+"/admin/"+current_element.dataset.section+"/"+current_sorting_method.id+"_"+
                    current_sorting_method.dataset.sorting_mode;
    } else {
        var url = localization+"/admin/"+current_element.dataset.section+"/"+current_element.dataset.parent_keyword+
                    "/page/1/"+show_invisible+"/"+current_sorting_method.id+"_"+
                    current_sorting_method.dataset.sorting_mode+"/"+directories_or_files_first_value;
    }
    window.location.href = url;
}

//This function is required to show folders(albums) or articles(pictures) first.
$("input[type='radio']").change(function() {
    var directories_or_files_first_value = $(this).val();
    var element_with_sorting_info = document.querySelector('#'+$(this)[0].id);
    var current_sorting_method_element = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');
    
    directories_or_files_first(element_with_sorting_info, 
                    current_sorting_method_element.id+"_"+current_sorting_method_element.dataset.current_sorting_mode, 
                    directories_or_files_first_value, $('input[name="show_only_visible"]').val());
});

function directories_or_files_first(element_with_sorting_info, sorting_method_and_mode, directories_or_files_first_value, show_invisible) {
    //If it is an english localization, we don't need to show it, because it is a default localization.
    var localization = (element_with_sorting_info.dataset.localization === "en") ? "" : "/ru";

    var url = localization+"/admin/"+element_with_sorting_info.dataset.section+"/"+
              element_with_sorting_info.dataset.parent_keyword+
              "/page/1/"+show_invisible+"/"+sorting_method_and_mode+"/"+directories_or_files_first_value;
    window.location.href = url;
}

//This set of functions is required if we need to display or hide invisible items.
$('input[name="show_only_visible"]').change(function(){
    //Variables below contain necessary information to make a proper route.
    var show_invisible = $(this).val();
    var element_for_info = $("input[name='directories_or_files_first']:checked");
    var current_sorting_method_element = document.querySelector('.admin-panel-articles-article-and-folder-header-caret-used');   
    var localization = (element_for_info[0].dataset.localization === "en") ? "" : "/ru";  
    var sorting_method_and_mode = current_sorting_method_element.id+"_"+current_sorting_method_element.dataset.current_sorting_mode;
    
    if (show_invisible === "all") {   
        var url = localization+"/admin/"+element_for_info[0].dataset.section+"/"+
            element_for_info[0].dataset.parent_keyword+
            "/page/1/"+"only_visible"+"/"+sorting_method_and_mode+"/"+element_for_info[0].value;
    } else {
        var url = localization+"/admin/"+element_for_info[0].dataset.section+"/"+
            element_for_info[0].dataset.parent_keyword+
            "/page/1/"+"all"+"/"+sorting_method_and_mode+"/"+element_for_info[0].value;
    } 
    window.location.href = url;
});
/*--------------------------------------------------------*/