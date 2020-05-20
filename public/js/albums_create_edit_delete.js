/*For partial view in fancybox window we need to make separate scripts file.
  Otherwise scipts are not working properly.*/
/*We need the script below to make the button submitting forms.
  I can not use submit input in that particular case, because 
  "word-wrap: break-word" doesn't work for submit input. It works
  only for buttons*/


$( document ).ready(function() {
    //We need the following lines to make ajax requests work.
    //There are special tokens used for security. We need to add them in all heads
    //and also ajax should be set up to pass them.
    $( document ).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });  
   
    //Few lines below are made for cancel button, which is closing opened window 
    //without saving or deleting anything.
    var button_cancel = document.getElementById('admin_panel_albums_create_edit_delete_album_controls_button_cancel');
    
    //We actually don't need the check below, but I let it just in case
    if (button_cancel !== null) {   
        button_cancel.onclick = function() {
            if (typeof window.parent.$.fancybox!=='undefined'){
                window.parent.$.fancybox.close();
            }
        };
    }
    
    //Below we are making a functionality for Search button
    var button_search = document.getElementById('parent_albums_search_button');
    var parent_search =document.getElementById('included_in_album_with_id');
    
    button_search.onclick = function() {
            $.ajax({
                type: "POST",
                //We need to take url from attributes because we have two
                //localizations of the website.
                url: "findParents",
                data: {parent_search: parent_search.value},
                success:function(data) {
                        //alert(data.name);
                        parent_search.value = data.name;
                    }
            });
    };
});
