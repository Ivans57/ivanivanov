/*The only purpose of the following javascript is to close its form and reload 
 its parent page*/

$( document ).ready(function() {
    var parent_keyword_and_section;
    var parent_directory_is_empty;
    if (typeof window.parent.$.fancybox!=='undefined') {
        parent_keyword_and_section = document.querySelector('#parent_keyword_and_section');
        parent_directory_is_empty = document.querySelector('#parent_directory_is_empty');
        
        window.parent.$.fancybox.close();
    }
    
    if (parent_directory_is_empty.value === "1") {
        //Need to consider level 0 option (no parent).
        var url = "/admin/"+parent_keyword_and_section.dataset.section+"/"+parent_keyword_and_section.value+"/page/1/";
        parent.location.href = url;
    } else {
        parent.location.reload(true);
    }
});