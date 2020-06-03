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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
    var button_select_from_dropdown_list = document.getElementById('parent_albums_select_from_dropdown_list_button');
    var old_keyword = document.getElementById('old_keyword');
    var parent_id =document.getElementById('included_in_album_with_id');
    var album_list_container =document.getElementById('album_list_container');
    var form = document.getElementById('admin_panel_create_edit_delete_album_form');
    
    //We need to make an event on this as onsubmit function is not working properly.
    var button_submit = document.getElementById('admin_panel_albums_create_edit_delete_album_controls_button_submit');

    var url_to_find_parent;
    if (form.dataset.localization === "en") {
        url_to_find_parent = "/admin/albums/create_or_edit/findParents";
    } else {
        url_to_find_parent = "/ru/admin/albums/create_or_edit/findParents";
    }
    
    //Simple button_search.onclick function is not working properly, that's why we are going to do via event listener.
    button_search.addEventListener('click', function() {
        get_parents(form.dataset.localization, parent_search.value, old_keyword.value, url_to_find_parent, 1);
    });
    
    var url_for_parent_list;
    if (form.dataset.localization === "en") {
        url_for_parent_list = "/admin/albums/create_or_edit/getParentList";
    } else {
        url_for_parent_list = "/ru/admin/albums/create_or_edit/getParentList";
    }
    
    button_select_from_dropdown_list.addEventListener('click', function() {
        //get_parent_list(form.dataset.localization, url_for_parent_list, 1);
        get_parent_list_begining(form.dataset.localization, url_for_parent_list, 1);
    });
    
    //We don't need to do a request here, but I will do it to make a dropdwon list.
    //If I don't do it, function whicsh is supposed to close dropdown list area 
    //if I click out of it, will always close it after I open it.
    function get_parent_list_begining(localization, url, page) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page},
                success:function(data) {
                        //We need this variable to identify a line which will open a new lsi of included albums.
                        var line_id = "line_0";
                        album_list_container.insertAdjacentHTML("beforeend", "<ul \n\
                                                                class='admin-panel-albums-create-edit-album-album-drop-down-list'\n\
                                                                id='album_dropdown_list'> \n\
                                                                <li id='" + line_id + "'>\n\
                                                                    <span class='admin-panel-albums-create-edit-album-album-drop-down-list-item' \n\
                                                                    id='element_0'>Albums</span>\n\
                                                                </li>\n\
                                                                </ul>");
                        var album_list_element = document.getElementById('element_0');
                        if (data.parent_list_data.length > 0) {
                            album_list_element.insertAdjacentHTML("afterbegin", 
                            "<span class='admin-panel-albums-create-edit-album-album-drop-down-list-item-caret' data-line_id='" + line_id + "'></span>");
                        } else {
                            album_list_element.insertAdjacentHTML("afterbegin", 
                            "<span class='admin-panel-albums-create-edit-album-album-drop-down-list-item-empty-caret'></span>");
                        }
                        
                        //Then we need to do something after user is pressing on a caret.
                        caret_turn_and_request(localization, url, page);
                    }
            });
    }
    
    function get_parent_list(localization, url, page, line_id) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page},
                success:function(data) {
                        var nested_album_lists_parent = document.getElementById(line_id);
                        
                        nested_album_lists_parent.insertAdjacentHTML("beforeend", "<ul \n\
                                                                class='admin-panel-albums-create-edit-album-album-drop-down-list-nested'\n\
                                                                id='album_dropdown_list_for_" + line_id +
                                                                "'></ul>");
                        //Filling up albums drop dwon list.
                        var album_list = document.getElementById("album_dropdown_list_for_" + line_id);
                                             
                        data.parent_list_data.forEach(function(album_data) {
                            album_list.insertAdjacentHTML("beforeend", 
                                                        "<li id='line_" + album_data.AlbumId + "'>\n\
                                                        <span class='admin-panel-albums-create-edit-album-album-drop-down-list-item' \n\
                                                        id='element_" + album_data.AlbumId +"'>"
                                                        + album_data.AlbumName +
                                                        "</span></li>");
                                                        var album_list_element = document.getElementById('element_' + album_data.AlbumId);
                                                        if (album_data.HasChildren === true) {
                                                            album_list_element.insertAdjacentHTML("afterbegin", 
                                                            "<span class='admin-panel-albums-create-edit-album-album-drop-down-list-item-caret' \n\
                                                                data-line_id='line_" + album_data.AlbumId + "'></span>");
                                                        } else {
                                                            album_list_element.insertAdjacentHTML("afterbegin", 
                                                            "<span class='admin-panel-albums-create-edit-album-album-drop-down-list-item-empty-caret'></span>");
                                                        }
                        });
                        
                        //Then we need to do something after user is pressing on a caret.
                        caret_turn_and_request(localization, url, page);

                        //var initial_album_list = document.getElementById('element_0');
                        //initial_album_list.style.display='none';
                        //initial_album_list.offsetHeight; // no need to store this anywhere, the reference is enough
                        //initial_album_list.style.display='block';
                    }
            });
    }
    
    function caret_turn_and_request(localization, url, page) {
        var toggler = document.getElementsByClassName("admin-panel-albums-create-edit-album-album-drop-down-list-item-caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                //this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("admin-panel-albums-create-edit-album-album-drop-down-list-item-caret-down");
                //$("#album_list_container").empty();
                //Taking id from caret (this) instead of line. Can pass line's id in caret's data.
                var line_id = this.dataset.line_id;
                get_parent_list(localization, url, page, line_id);
            });
        }
    }
    
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
                                                              class='admin-panel-albums-create-edit-album-album-list-button'> \n\
                                                              <a href='#' \n\
                                                              class='admin-panel-albums-create-edit-album-album-list-button-link' \n\
                                                              id='parents_previous_page'>" + album_list_container.dataset.previous_page +
                                                              "</a> \n\
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
                                                          class='admin-panel-albums-create-edit-album-album-list-button'> \n\
                                                          <a href='#' \n\
                                                          class='admin-panel-albums-create-edit-album-album-list-element-button-link' \n\
                                                          id='parents_next_page'>" + album_list_container.dataset.next_page +
                                                          "</a> \n\
                                                          </div>");
                        }
                        
                        var parent_prev = document.getElementById('parents_previous_page');
                        
                        var parent_next = document.getElementById('parents_next_page');
                        
                        //Before applying an event to the object, need to make sure it exists,
                        //otherwise might be an error.
                        if (parent_prev !== null) {
                            parent_prev.addEventListener('click', function() {
                                //I will leave like this, because this function is not working with a variable.
                                $("#album_list_container").empty();
                                get_parents(localization, parent_name, keyword, url, 
                                            data.pagination_info.previousPage);
                            });
                        }
                        
                        //Before applying an event to the object, need to make sure it exists,
                        //otherwise might be an error.
                        if (parent_next !== null) {
                            parent_next.addEventListener('click', function() {
                                //I will leave like this, because this function is not working with a variable.
                                $("#album_list_container").empty();
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
   
    //Here we need to assign proper form fields for keyword and selected parent name.
    function assignIDAndName(zEvent) {
        //-- this and the parameter are special in event handlers.
        var album_id  = this.getAttribute("data-id");
        //We are working with album_id as a string, because when we are getting data from backend, 
        //we are getting them as json with strings.
        if (album_id !== "0") {
            var path = this.innerHTML;
            var path_array = path.split(" / ");
            //We need to take the last element of the array.
            var album_name = path_array[path_array.length - 1];
            parent_search.value = album_name;
            parent_id.value = album_id;
        } else {
            parent_search.value = null;
        }
        $("#album_list_container").empty();
    }
         
    //We need to make this event as onsubmit function is not working properly.
    if (button_submit !== null) {   
        button_submit.onclick = function() {
            if (parent_search.value === "") {
                parent_id.value = "0";
            }
        };
    }
    
    //The following piece of code closes drop down list for potential parents
    //after clicking out of it.
    $(window).click(function(event) {
        //We need to empty album_list_container only if it has something.
        //Funtion trim will remove extra spaces.
        if ($.trim($("#album_list_container").html()) !== ""){
            var child = $(event.target);
            //We need to take 0 element of event.target, because it is an array.
            var parent_check = checkParent(album_list_container, child[0]);
            if (parent_check === false) {
                $("#album_list_container").empty();
            }
        }
    });
    
    //We need this function to check whether some element is a child of another element.
    function checkParent(parent, child) { 
        if (parent.contains(child)) 
            return true; 
            return false; 
    }
});

