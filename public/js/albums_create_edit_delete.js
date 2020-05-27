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
    var parent_search =document.getElementById('included_in_album_with_name');
    var button_search = document.getElementById('parent_albums_search_button');
    var keyword = document.getElementById('keyword');
    var parent_id =document.getElementById('included_in_album_with_id');
    var album_list_container =document.getElementById('album_list_container');
    var form = document.getElementById('admin_panel_create_edit_delete_album_form');
    
    //We need to make an event on this as onsubmit function is not working properly.
    var button_submit = document.getElementById('admin_panel_albums_create_edit_delete_album_controls_button_submit');
    
    //var parent_next;
    //var parent_prev;
    
    var url;
    if (form.dataset.localization === "en") {
        url = "/admin/albums/create_or_edit/findParents";
    } else {
        url = "/ru/admin/albums/create_or_edit/findParents";
    }
    
    //Simple button_search.onclick function is not working properly, that's why we are going to do via event listener.
    button_search.addEventListener('click', function() {
        get_parents(form.dataset.localization, parent_search.value, keyword.value, url, 1);
    });
    
    function get_parents(localization, parent_name, keyword, url, page) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page, parent_search: parent_name, keyword: keyword},
                success:function(data) {
                        //We will always assign it, because if we don't do that,
                        //after turning pages, that value will always disappear from search field.
                        parent_search.value = parent_name;
                        //Making empty drop down list with album links.
                        album_list_container.insertAdjacentHTML("beforeend", "<div \n\
                                                                class='admin-panel-albums-create-edit-album-album-list'\n\
                                                                id='album_list'> \n\
                                                                </div>");
                    
                        //Filling up albums drop dwon list.
                        var album_list = document.getElementById('album_list');
                        
                        if (data.pagination_info.previousPage !== null) {
                            album_list.insertAdjacentHTML("beforeend", "<div \n\
                                                              class='admin-panel-albums-create-edit-album-album-list-element'> \n\
                                                              <a href='#' \n\
                                                              class='admin-panel-albums-create-edit-album-album-list-element-link' \n\
                                                              id='parents_previous_page'>Previous Page\n\
                                                              </a> \n\
                                                              </div>");
                        }
                        
                        data.albums_data.forEach(function(album_data) {
                            album_list.insertAdjacentHTML("beforeend", "<div \n\
                                                          class='admin-panel-albums-create-edit-album-album-list-element'> \n\
                                                          <a href='#' \n\
                                                          class='admin-panel-albums-create-edit-album-album-list-element-link' \n\
                                                          data-id='" + album_data[0] +"'>" 
                                                          + album_data[1] + "</a> \n\
                                                          </div>");
                        });
                        
                        if (data.pagination_info.nextPage !== null) {
                        album_list.insertAdjacentHTML("beforeend", "<div \n\
                                                          class='admin-panel-albums-create-edit-album-album-list-element'> \n\
                                                          <a href='#' \n\
                                                          class='admin-panel-albums-create-edit-album-album-list-element-link' \n\
                                                          id='parents_next_page'>Next Page\n\
                                                          </a> \n\
                                                          </div>");
                        }
                        
                        var parent_prev = document.getElementById('parents_previous_page');
                        
                        var parent_next = document.getElementById('parents_next_page');
                        
                        //Before applying an event to the object, need to make sure it exists,
                        //otherwise might be an error.
                        if (parent_prev !== null) {
                            parent_prev.addEventListener('click', function() {
                                get_parents(localization, parent_name, keyword, url, 
                                            data.pagination_info.previousPage);
                            });
                        }
                        
                        //Before applying an event to the object, need to make sure it exists,
                        //otherwise might be an error.
                        if (parent_next !== null) {
                            parent_next.addEventListener('click', function() {
                                get_parents(localization, parent_name, keyword, url, 
                                            data.pagination_info.nextPage);
                            });
                        }
                        
                        //Need to attach an event which will select albums keyword and name and assign them to proper form fields.
                        var album_list_element_links = 
                                document.getElementsByClassName("admin-panel-albums-create-edit-album-album-list-element-link");
                        
                        for (i = 0; i < album_list_element_links.length; i++) {
                            album_list_element_links[i].addEventListener ("click", assignIDAndName, false);
                        }
                    }
            });
    }
   
    window.onclick = function(event) {
        //We need this to close a drop down list.
        if (!event.target.matches('.admin-panel-albums-create-edit-album-controls-button-search')) {
            $("#album_list_container").empty();
        }
    };
    
    //Here we need to assign proper form fields for keyword and selected parent name.
    function assignIDAndName(zEvent) {
        //-- this and the parameter are special in event handlers.
        var album_id  = this.getAttribute ("data-id");
        //We are working with album_id as a string, because when we are getting data from backend, 
        //we are getting them as json with strings.
        if (album_id !== "0") {
            var path = this.innerHTML;
            var path_array = path.split(" ");
            //We need to take the last element of the array.
            var album_name = path_array[path_array.length - 1];
            parent_search.value = album_name;
            parent_id.value = album_id;
        } else {
            parent_search.value = null;
        }
    }
    
    //We need to make this event as onsubmit function is not working properly.
    if (button_submit !== null) {   
        button_submit.onclick = function() {
            if (parent_search.value === "") {
                parent_id.value = "0";
            }
        };
    }
});