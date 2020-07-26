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
    
    //Below we are making a functionality for Search button
    var parent_search =document.getElementById('included_in_directory_with_name');
    var button_search = document.getElementById('parent_directory_search_button');
    var button_select_from_dropdown_list = document.getElementById('parent_directory_select_from_dropdown_list_button');
    var old_keyword = document.getElementById('old_keyword');
    var parent_id = document.getElementById('included_in_directory_with_id');
    var directory_list_container =document.getElementById('directory_list_container');
    var form = document.getElementById('admin_panel_create_edit_entity_form');
    
    //We need to make an event on this as onsubmit function is not working properly.
    var button_submit = document.getElementById('button_submit');

    //We need to make this event as onsubmit function is not working properly.
    if (button_submit !== null) {   
        button_submit.onclick = function() {
            if (parent_search.value === "") {
                parent_id.value = "0";
            }
        };
    }

    //Functions for parent search in database.

    var url_to_find_parent;
    if (form.dataset.localization === "en") {
        url_to_find_parent = "/admin/findParents";
    } else {
        url_to_find_parent = "/ru/admin/findParents";
    }
    
    //Simple button_search.onclick function is not working properly, that's why we are going to do via event listener.
    button_search.addEventListener('click', function() {
        get_parents(form.dataset.localization, parent_search.value, old_keyword.value, url_to_find_parent, form.dataset.section, 1, form.dataset.mode);
    });
    
    //Here is a function for create or edit window parent search in database.
    function get_parents(localization, parent_name, keyword, url, section, page, mode) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page, parent_search: parent_name, keyword: keyword, section: section, mode: mode},
                success:function(data) {
                        //We will always assign it, because if we don't do that,
                        //after turning pages, that value will always disappear from search field.
                        parent_search.value = parent_name;
                        //Making empty drop down list with directory links.
                        directory_list_container.insertAdjacentHTML("beforeend", "<div \n\
                                                                class='admin-panel-create-edit-directory-directory-list'\n\
                                                                id='directory_list'> \n\
                                                                </div>");
                    
                        //Filling up directory drop dwon list.
                        var directory_list = document.getElementById('directory_list');
                        
                        if (data.pagination_info.previousPage !== null) {
                            directory_list.insertAdjacentHTML("beforeend", "<div \n\
                                                              class='admin-panel-create-edit-directory-directory-list-button'> \n\
                                                              <a href='#' \n\
                                                              class='admin-panel-create-edit-directory-directory-list-button-link' \n\
                                                              id='parents_previous_page'>" + directory_list_container.dataset.previous_page +
                                                              "</a> \n\
                                                              </div>");
                        }                       
                        data.directories_data.forEach(function(directory_data) {
                            directory_list.insertAdjacentHTML("beforeend", "<div \n\
                                                          class='admin-panel-create-edit-directory-directory-list-element'> \n\
                                                          <a href='#' \n\
                                                          class='admin-panel-create-edit-directory-directory-list-element-link' \n\
                                                          data-id='" + directory_data[0] +"'>" 
                                                          + directory_data[1] + "</a> \n\
                                                          </div>");
                        });                     
                        if (data.pagination_info.nextPage !== null) {
                        directory_list.insertAdjacentHTML("beforeend", "<div \n\
                                                          class='admin-panel-create-edit-directory-directory-list-button'> \n\
                                                          <a href='#' \n\
                                                          class='admin-panel-create-edit-directory-directory-list-element-button-link' \n\
                                                          id='parents_next_page'>" + directory_list_container.dataset.next_page +
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
                                $("#directory_list_container").empty();
                                get_parents(localization, parent_name, keyword, url, section, 
                                            data.pagination_info.previousPage, form.dataset.mode);
                            });
                        }
                        
                        //Before applying an event to the object, need to make sure it exists,
                        //otherwise might be an error.
                        if (parent_next !== null) {
                            parent_next.addEventListener('click', function() {
                                //I will leave like this, because this function is not working with a variable.
                                $("#directory_list_container").empty();
                                get_parents(localization, parent_name, keyword, url, section, 
                                            data.pagination_info.nextPage, form.dataset.mode);
                            });
                        }
                        
                        //Need to attach an event which will select keyword and name and assign them to proper form fields.
                        var directory_list_element_links = 
                                document.getElementsByClassName("admin-panel-create-edit-directory-directory-list-element-link");
                        
                        for (i = 0; i < directory_list_element_links.length; i++) {
                            directory_list_element_links[i].addEventListener ("click", assignIDAndName, false);
                        }
                    }
            });
    }
   
    //Here we need to assign proper form fields for keyword and selected parent name.
    function assignIDAndName(zEvent) {
        //-- this and the parameter are special in event handlers.
        var directory_id  = this.getAttribute("data-id");
        //We are working with directory_id as a string, because when we are getting data from backend, 
        //we are getting them as json with strings.
        if (directory_id !== "0") {
            var path = this.innerHTML;
            var path_array = path.split(" / ");
            //We need to take the last element of the array.
            var directory_name = path_array[path_array.length - 1];
            parent_search.value = directory_name;
            parent_id.value = directory_id;
        } else {
            parent_search.value = null;
        }
        $("#directory_list_container").empty();
    }
    
    //Functions for parent dropdown list (tree).
    
    var url_for_parent_list;
    if (form.dataset.localization === "en") {
        url_for_parent_list = "/admin/getParentList";
    } else {
        url_for_parent_list = "/ru/admin/getParentList";
    }
    
    button_select_from_dropdown_list.addEventListener('click', function() {
        //In this case we need a page number just to make a request and find out if a root directory has at least
        //one directory or no, so we can know if a caret is required or no.
        if (old_keyword.value == "" && parent_search.value == "") {
            parent_id.value = 0;
        }
        get_parent_list(form.dataset.localization, url_for_parent_list, parent_id.value, 1, old_keyword.value, form.dataset.section, form.dataset.mode);
    });
    
    //Here is a function for create or edit window parent dropdown list.
    function get_parent_list(localization, url, parent_id, page, old_keyword_value, section, mode) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page, parent_id: parent_id, keyword_of_directory_to_exclude: old_keyword_value, section: section, mode: mode},
                success:function(data) {
                    //In case we need to get an opened list, all elements of data array 
                    //will be arrays. If we get a closed list, all elements of data array
                    //will be numbers.
                    if (Array.isArray(data.parent_list_data[0]) === true) {
                        make_opened_parent_list(data, localization, url, page, old_keyword_value, section);
                    } else {
                        make_closed_parent_list(data, localization, url, page, old_keyword_value, section);
                    }                       
                    }
            });
    }
    
    //This function will make a parent list when being edited item has a parent.
    function make_opened_parent_list(data, localization, url, page, old_keyword_value, section) {
        var line_id = make_initial_parent_list_without_carets(data, localization, url, page);
        
        var directory_list_element = document.getElementById('element_0');
        
        //directory_list_element in this case will always have some children.
        //If it doesn't, make_closed_parent_list will be called.
        directory_list_element.insertAdjacentHTML("afterbegin", 
                                            "<span class='admin-panel-create-edit-directory-drop-down-list-item-caret-down' \n\
                                            data-line_id='" + line_id + "' data-record_id=0></span>");
        
        var parent_node_id = 0;
        var new_line_id = "line_0";
        var previous_page;
        var next_page;
        
        for (i = 0; i < data.parent_list_data.length; i++) {
            var parent_list_data = data.parent_list_data[i];
            previous_page = data.pagination_info[i].previousPage;
            next_page = data.pagination_info[i].nextPage;
            make_included_parent_list(localization, url, new_line_id, parent_node_id, parent_list_data, previous_page, next_page, 
                                        old_keyword_value, section);
            
            //I can't include this in make_included_parent_list function, 
            //as in another funtion which uses make_included_parent_list function,
            //we don't need it.
            parent_list_data.forEach(function(directory_data) {
                if (directory_data.isOpened === true) {
                    parent_node_id = directory_data.DirectoryId;
                }
            });
            new_line_id = "line_" + parent_node_id;
        }
        
        //Need to add events for opened lists.
        //No need to worry about closed ones, as make_included_parent_list 
        //function has already done it.
        caret_turn_back(localization, url, page, directory_list_container, old_keyword_value, section);
        //Below we are making an event for list element selection.
        select_from_dropdown_list(directory_list_container);
    }
    
    //This function will make a parent list when there is no parent for being edited item.
    function make_closed_parent_list(data, localization, url, page, old_keyword_value, section) {
        var line_id = make_initial_parent_list_without_carets();
        
        var directory_list_element = document.getElementById('element_0');
        if (data.parent_list_data.length > 0) {
            directory_list_element.insertAdjacentHTML("afterbegin", 
            "<span class='admin-panel-create-edit-directory-drop-down-list-item-caret' \n\
            data-line_id='" + line_id + "' data-record_id=0></span>");
        } else {
            directory_list_element.insertAdjacentHTML("afterbegin", 
            "<span class='admin-panel-create-edit-directory-drop-down-list-item-empty-caret'></span>");
        }
                        
        //Below we are assigning an event for that cse when user is pressing on a caret.
        caret_turn_and_request(localization, url, page, directory_list_container, old_keyword_value, section);
        //Below we are making an event for list element selection.
        select_from_dropdown_list(directory_list_container);
    }
    
    //I have made a function, because I am going to use the same code at least two times.
    function make_initial_parent_list_without_carets() {
        //We need this variable to identify a line which will open a new list of included directories.
        var line_id = "line_0";
        directory_list_container.insertAdjacentHTML("beforeend", "<ul \n\
                                                class='admin-panel-create-edit-directory-drop-down-list'> \n\
                                                    <li id='" + line_id + "'>\n\
                                                        <span \n\
                                                        class='admin-panel-create-edit-directory-drop-down-list-item' \n\
                                                        id='element_0'> \n\
                                                            <span \n\
                                                            class='admin-panel-create-edit-directory-drop-down-list-item-name' \n\
                                                            data-directory_id=0>" 
                                                            + directory_list_container.dataset.root + 
                                                            "</span></span>\n\
                                                    </li> \n\
                                                </ul>");
        return line_id;
    }
    
    //This function is working when user is clicking on the caret to get what is
    //included in its item.
    function get_included_parent_list(localization, url, page, line_id, parent_node_id, old_keyword_value, section, mode) {
        $.ajax({
                type: "POST",
                url: url,
                data: {localization: localization, page: page, parent_node_id: parent_node_id, 
                    keyword_of_directory_to_exclude: old_keyword_value, section: section, mode: mode},
                success:function(data) {                     
                        make_included_parent_list(localization, url, line_id, parent_node_id, data.parent_list_data, 
                                                data.pagination_info.previousPage, data.pagination_info.nextPage, old_keyword_value, section);
                    }
            });
    }
    
     //I have made a function, because I am going to use the same code at least two times.
    function make_included_parent_list(localization, url, line_id, parent_node_id, parent_list_data, previous_page, next_page, 
                                        old_keyword_value, section) {
        var nested_directory_lists_parent = document.getElementById(line_id);
                        
        nested_directory_lists_parent.insertAdjacentHTML("beforeend", "<ul \n\
                                                    class='admin-panel-create-edit-directory-drop-down-list-nested'\n\
                                                    id='directory_dropdown_list_for_" + line_id +
                                                    "'></ul>");
                                                        
        //Filling up directory dropdwon list.
        var directory_list = document.getElementById("directory_dropdown_list_for_" + line_id);
                        
        //Here we need to draw pagination button in case there is more than one page of records.
        if (previous_page !== null) {
            directory_list.insertAdjacentHTML("beforeend", "<div \n\
                                        class='admin-panel-create-edit-directory-drop-down-list-button'> \n\
                                            <a href='#' \n\
                                            class='admin-panel-create-edit-directory-drop-down-list-button-link' \n\
                                            id='parents_previous_page_for_" + line_id + "'>"
                                                + directory_list_container.dataset.previous_page +
                                            "</a> \n\
                                        </div>");
        }
                    
        parent_list_data.forEach(function(directory_data) {
            directory_list.insertAdjacentHTML("beforeend", 
                                        "<li id='line_" + directory_data.DirectoryId + "'> \n\
                                            <span class='admin-panel-create-edit-directory-drop-down-list-item' \n\
                                            id='element_" + directory_data.DirectoryId +"'> \n\
                                                <span class='admin-panel-create-edit-directory-drop-down-list-item-name' \n\
                                                data-directory_id=" + directory_data.DirectoryId +">"
                                                    + directory_data.DirectoryName +
                                            "</span></span></li>");
                                    
        var directory_list_element = document.getElementById('element_' + directory_data.DirectoryId);
        if (directory_data.HasChildren === true && directory_data.isOpened === true) {
            directory_list_element.insertAdjacentHTML("afterbegin", 
                                                "<span class='admin-panel-create-edit-directory-drop-down-list-item-caret-down' \n\
                                                data-line_id='line_" + directory_data.DirectoryId + "' \n\
                                                data-record_id=" + directory_data.DirectoryId + "></span>");
        } else if (directory_data.HasChildren === true) {
            directory_list_element.insertAdjacentHTML("afterbegin", 
                                                "<span class='admin-panel-create-edit-directory-drop-down-list-item-caret' \n\
                                                data-line_id='line_" + directory_data.DirectoryId + "' \n\
                                                data-record_id=" + directory_data.DirectoryId + "></span>");
        } else {
            directory_list_element.insertAdjacentHTML("afterbegin", 
                                                "<span class='admin-panel-create-edit-directory-drop-down-list-item-empty-caret'> \n\
                                                </span>");
        }
        //Need to make parent element visible. For opened dropdown list only.
        if (directory_data.inFocus) {
            directory_list_element.classList.add("admin-panel-create-edit-directory-drop-down-list-item-selected");
        }
        });
                        
        //Below we are assigning an event for that cse when user is pressing on a caret.
        //We need to pass as a page argument, the first page, because if we pass a page
        //variable, there might be wrong page and drop down list won't work properly.
        caret_turn_and_request(localization, url, 1, directory_list, old_keyword_value, section);
        //Below we are making an event for list element selection.
        select_from_dropdown_list(directory_list);
                                                                     
        if (next_page !== null) {
            directory_list.insertAdjacentHTML("beforeend", "<div \n\
                                                        class='admin-panel-create-edit-directory-drop-down-list-button'> \n\
                                                            <a href='#' \n\
                                                            class='admin-panel-create-edit-directory-drop-down-list-button-link' \n\
                                                            id='parents_next_page_for_" + line_id + "'>" 
                                                                + directory_list_container.dataset.next_page +
                                                            "</a> \n\
                                                        </div>");
        }
                    
        var parent_prev = document.getElementById('parents_previous_page_for_'+ line_id);
        var parent_next = document.getElementById('parents_next_page_for_' + line_id);
                    
        if (parent_prev !== null) {
            parent_prev.addEventListener('click', function() {
            var element = document.querySelector("#" + directory_list.id);
            //Here we need to remove nested list (ul) from DOM.
            element.parentNode.removeChild(element);
            //Here we need to turn the next page.
            get_included_parent_list(localization, url, previous_page, line_id, parent_node_id, old_keyword_value, section, form.dataset.mode);
            });
        }
                    
        if (parent_next !== null) {
            parent_next.addEventListener('click', function() {
            var element = document.querySelector("#" + directory_list.id);
            //Here we need to remove nested list (ul) from DOM.
            element.parentNode.removeChild(element);
            //Here we need to turn the next page.
            get_included_parent_list(localization, url, next_page, line_id, parent_node_id, old_keyword_value, section, form.dataset.mode);
            });
        }
    }
    
    //This function makes an event listener for dropdown list elements.
    //After click on it, it becomes selected, its name is going to parent search field, just to make it visible, what has been selected.
    //Selected parent's id then is being copied to a hidden field.
    function select_from_dropdown_list(parent_container) {
        //Need to assign events only for new elelements, otherwise system will call the same event for more than one time,
        //which will cause errors.
        var current_item = parent_container.getElementsByClassName("admin-panel-create-edit-directory-drop-down-list-item-name");
        var i;

        for (i = 0; i < current_item.length; i++) {
            current_item[i].addEventListener("click", function(){
                //If user chooses a root (e.g. Albums, without any directory(e.g. album)),
                //then parent search area should be left blank.               
                if (this.innerText === directory_list_container.dataset.root) {
                    parent_search.value = null;
                } else {
                    parent_search.value = this.innerText;
                }
                parent_id.value = this.dataset.directory_id;
                $("#directory_list_container").empty();
            });
        }
    }
    
    //This function turns down a caret of element of parent dropdown list and sends a reuqest to get its children.
    //After the caret has been turned and request has been already send this functionality shouldn't be applied for
    //the element. We need to remove it from the element. To cancel this function for the element, we need to make it as a separate
    //function with its personal name. In our case it is turn_caret_and_get_children.
    function caret_turn_and_request(localization, url, page, parent_container, old_keyword_value, section) {
        //Need to assign events only for new elelements, otherwise system will call the same event for more than one time,
        //which will cause errors.
        var current_item = parent_container.getElementsByClassName("admin-panel-create-edit-directory-drop-down-list-item-caret");
        var i;

        for (i = 0; i < current_item.length; i++) {
            current_item[i].addEventListener("click", turn_caret_and_get_children, false);
            //If we use a function with events, we cannot pass arguments as normal.
            current_item[i].localization = localization;
            current_item[i].url = url;
            current_item[i].page = page;
            current_item[i].old_keyword_value = old_keyword_value;
            current_item[i].section = section;
        }
    }
    
    //This function will work only for opened dropdown list.
    function caret_turn_back(localization, url, page, parent_container, old_keyword_value, section) {
        //Need to assign events only for new elelements, otherwise system will call the same event for more than one time,
        //which will cause errors.
        var current_item = parent_container.getElementsByClassName("admin-panel-create-edit-directory-drop-down-list-item-caret-down");
        var i;

        for (i = 0; i < current_item.length; i++) {
            current_item[i].addEventListener("click", turn_caret_back_and_remove_children, false);
            current_item[i].localization = localization;
            current_item[i].url = url;
            current_item[i].page = page;
            current_item[i].old_keyword_value = old_keyword_value;
            current_item[i].section = section;
        }
    }
    
    //If we use a function with events, we cannot pass arguments as normal.
    function turn_caret_and_get_children(event) {
        event.currentTarget.classList.remove("admin-panel-create-edit-directory-drop-down-list-item-caret");
        event.currentTarget.classList.add("admin-panel-create-edit-directory-drop-down-list-item-caret-down");
        //After an element is open, need to remove its onclick event listener, otherwise dropdown list won't work properly.
        //Need to assign it again when closing an element.
        event.currentTarget.removeEventListener("click", turn_caret_and_get_children);
        event.currentTarget.addEventListener("click", turn_caret_back_and_remove_children, false);
        //Taking id from caret (this) instead of line. Can pass line's id in caret's data.
        get_included_parent_list(event.currentTarget.localization, event.currentTarget.url, 
                        event.currentTarget.page, event.currentTarget.dataset.line_id, event.currentTarget.dataset.record_id, 
                        event.currentTarget.old_keyword_value, event.currentTarget.section, form.dataset.mode);
    }
    
    //This functions returns a caret to its noraml position and removes items children from the list.
    function turn_caret_back_and_remove_children(event) {
        event.currentTarget.classList.remove("admin-panel-create-edit-directory-drop-down-list-item-caret-down");
        event.currentTarget.classList.add("admin-panel-create-edit-directory-drop-down-list-item-caret");
        event.currentTarget.addEventListener("click", turn_caret_and_get_children, false);
        event.currentTarget.removeEventListener("click", turn_caret_back_and_remove_children);
        var children_to_remove = document.getElementById("directory_dropdown_list_for_" + event.currentTarget.dataset.line_id);
        children_to_remove.remove();
    }
    
    
    //Three functions below are supposed to close parent search and parent dropdown list
    //after clicking out of it or on one of its lines with records.
    
    //The following piece of code closes drop down list for potential parents
    //after clicking out of it.
    $(window).click(function(event) {
        //We need to empty directory_list_container only if it has something.
        //Funtion trim will remove extra spaces.
        if ($.trim($("#directory_list_container").html()) !== ""){
            var child = $(event.target);
            //Please see the explanation where the function is declared.
            var existence_check = checkIfExists(child[0]);
            //We need to take 0 element of event.target, because it is an array.
            var parent_check = checkParent(directory_list_container, child[0]);
            if (existence_check === true && parent_check === false) {
                $("#directory_list_container").empty();
            }
        }
    });
    
    //Here is a check if a target element exists.
    //The problem is - when a dropdown list is open and we click a pagination button,
    //it destroys its parent element and itself, so when it comes to check whether element is within container,
    //system cannot see it and closes the window. It shouldn't happen.
    function checkIfExists(element) { 
        if (document.contains(element)) 
            return true; 
            return false; 
    }
    
    //We need this function to check whether some element is a child of another element.
    function checkParent(parent, child) { 
        if (parent.contains(child)) 
            return true; 
            return false; 
    }
});

