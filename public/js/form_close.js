/*The only purpose of the following javascript is to close its form and reload 
 its parent page*/

$( document ).ready(function() {
    var action_element = document.querySelector('#action');
    //The variable below is required to make proper actions when pop up window closes.
    var action = action_element.value;
    var parent_keyword_and_section;
    if (action === 'destroy') {
        var parent_directory_is_empty;
    }
    if (typeof window.parent.$.fancybox!=='undefined') {
        parent_keyword_and_section = document.querySelector('#parent_keyword_and_section');
        if (action === 'destroy') {
            parent_directory_is_empty = document.querySelector('#parent_directory_is_empty');
        }
        window.parent.$.fancybox.close();
    }
    
    if (action === 'destroy' || action === 'update') {
        var url;
        var localization = (parent_keyword_and_section.dataset.localization === "en") ? "" : "/ru";
    }
    
    //In search mode after delete or edit items always need to redirect to root element (folder or album) to avoid errors.
    //Before using this javascript all necessary variables need to be assigned to meet the condition which redirects to the root.
    if (action === 'destroy') {
        //If parent directory becomes empty after delete of an item, there are no filters and sorting required,
        //and need to relocate to the location.
        if (parent_directory_is_empty.value === "1") {
            if (parent_keyword_and_section.value === "0") {
                url = localization+"/admin/"+parent_keyword_and_section.dataset.section;
            } else {
                url = localization+"/admin/"+parent_keyword_and_section.dataset.section+"/"+parent_keyword_and_section.value+"/page/1/";
            }           
            parent.location.href = url;
        } else {
            parent.location.reload(true);
        }
    } else if (action === 'update' && $("#search_is_on").val() === "1") { //This condition is required for edit in search mode.
        //The check below is required if user visited before any other directory instead of root directory, so the user can be relocated to root directory.
        if (parent_keyword_and_section.value === "0") {
            url = localization+"/admin/"+parent_keyword_and_section.dataset.section;
        } else {
            url = localization+"/admin/"+parent_keyword_and_section.dataset.section+"/"+parent_keyword_and_section.value+"/page/1/";
        }
        parent.location.href = url;
    } else {
        parent.location.reload(true);
    }
});