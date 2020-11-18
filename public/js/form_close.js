/*The only purpose of the following javascript is to close its form and reload 
 its parent page*/

$( document ).ready(function() {
    var action_element = document.querySelector('#action');
    //The variable below is required to make proper actions when pop up window closes.
    var action = action_element.value;
    if (action === 'destroy') {
        var parent_keyword_and_section;
        var parent_directory_is_empty;
    }
    if (typeof window.parent.$.fancybox!=='undefined') {
        if (action === 'destroy') {
            parent_keyword_and_section = document.querySelector('#parent_keyword_and_section');
            parent_directory_is_empty = document.querySelector('#parent_directory_is_empty');
        }
        window.parent.$.fancybox.close();
    }
    if (action === 'destroy') {
        //If parent directory becomes empty after delete of an item, there are no filters and sorting required,
        //and need to relocate to the location.
        if (parent_directory_is_empty.value === "1") {
            var localization = (parent_keyword_and_section.dataset.localization === "en") ? "" : "/ru";
            var url;
            if (parent_keyword_and_section.value === "0") {
                url = localization+"/admin/"+parent_keyword_and_section.dataset.section;
            } else {
                url = localization+"/admin/"+parent_keyword_and_section.dataset.section+"/"+parent_keyword_and_section.value+"/page/1/";
            }           
            parent.location.href = url;
        } else {
            parent.location.reload(true);
        }
    } else {
        parent.location.reload(true);
    }
});