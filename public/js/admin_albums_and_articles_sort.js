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
    albums_or_articles_sort(current_element.id, current_element);
});
    
//Sorting by creation date and time.
$( "#sort_by_creation" ).click(function() {
    var current_element = document.querySelector('#sort_by_creation');
    //The last parameter have only included items.
    albums_or_articles_sort(current_element.id, current_element);
});
    
//Sorting by update date and time.
$( "#sort_by_update" ).click(function() {
    var current_element = document.querySelector('#sort_by_update');
    //The last parameter have only included items.
    albums_or_articles_sort(current_element.id, current_element);
});
    
//The function below is making a link to do sorting and going to it.
//For elements on level 0 and for different level elements will be different links, for that reason we need that parameter.
//The last parameter have only included items.
function albums_or_articles_sort(sorting_method, current_element) {
    //If it is an english localization, we don't need to show it, because it is a default localization.
    var localization = (current_element.dataset.localization === "en") ? "" : "/ru";
    var current_sorting_method = document.querySelector('#'+sorting_method);
    if (current_element.dataset.is_level_zero === "1") {
        var url = localization+"/admin/"+current_element.dataset.section+"/"+current_sorting_method.id+"_"+
                    current_sorting_method.dataset.sorting_mode;
    } else {
        var url = localization+"/admin/"+current_element.dataset.section+"/"+current_element.dataset.parent_keyword+
                    "/page/1/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
    }
    window.location.href = url;
}

/*--------------------------------------------------------*/