/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Keywords*/

$( document ).ready(function() {
    //We need the following lines to make ajax requests work.
    //There are special tokens used for security. We need to add them in all heads
    //and also ajax should be set up to pass them.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //Making list of all elements with our class.
    var add_keyword_buttons = document.querySelectorAll('.admin-panel-keywords-add-keyword-button');
    var add_keyword_links = document.querySelectorAll('.admin-panel-keywords-add-keyword-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_keyword_buttons.length; i++) {
        clickAddKeywordButton(add_keyword_buttons[i], add_keyword_links[i]);
    }
    function clickAddKeywordButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-keywords-add-keyword-button');
            button.classList.add('admin-panel-keywords-add-keyword-button-pressed');
            link.classList.remove('admin-panel-keywords-add-keyword-button-link');
            link.classList.add('admin-panel-keywords-add-keyword-button-link-pressed');
        });
    }

    //We need this script to open new keyword create page in fancy box window.
    $(".admin-panel-keywords-add-keyword-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '355px',
                    'height' : '420px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            unclickButton(document.querySelector('.admin-panel-keywords-add-keyword-button-pressed'), 
                          document.querySelector('.admin-panel-keywords-add-keyword-button-link-pressed'));

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-keywords-add-keyword-button-pressed');
                button.classList.add('admin-panel-keywords-add-keyword-button');
                link.classList.remove('admin-panel-keywords-add-keyword-button-link-pressed');
                link.classList.add('admin-panel-keywords-add-keyword-button-link');
            }
        }
    });    
    
    $(document).on("click", ".admin-panel-keywords-edit-delete-control-button", function() {
        $(this).removeClass("admin-panel-keywords-edit-delete-control-button").addClass("admin-panel-keywords-edit-delete-control-button-pressed");
    });
    
    function keywords_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        unclickButton(document.querySelector('.admin-panel-keywords-edit-delete-control-button-pressed'));

        function unclickButton(button) {
            button.classList.remove('admin-panel-keywords-edit-delete-control-button-pressed');
            button.classList.add('admin-panel-keywords-edit-delete-control-button');
        }
    }
    
    $(document).on("click", "#keywords_button_edit", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, becuase we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            //Passing url as a parameter.
            edit_keyword(((selected_checkbox[0].dataset.localization === "en") ? "" : 
                           "/ru")+'/admin/keywords/'+selected_checkbox[0].dataset.keyword+'/edit/');
        }
    });
    
    function edit_keyword(url) {
        //We need this script to open existing Keyword edit page in fancy box window.
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '355px',
                    'height' : '420px',
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                keywords_control_button_view_change_after_fancybox_close();
            }
        });
    }

    $(document).on("click", "#keywords_button_delete", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items (keywords) separated by ";" sign.            
        
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                var keyword = all_checkboxes[i].dataset.keyword+';';
                selected_checkbox_data = selected_checkbox_data+keyword;
            }
        }
        if (selected_checkbox_data.length > 0) {
            //For different localization will be different height.
            //passing url as the first parameter and height as the second parameter.
            delete_keywords((((all_checkboxes[0].dataset.localization === "en") ? "" : "/ru")+'/admin/keywords/delete/'+selected_checkbox_data), 
                               ((all_checkboxes[0].dataset.localization === "en") ? "170px" : "200px"));
        }
    });

    function delete_keywords(url, height) {
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '380px',
                    'height' : height,
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                keywords_control_button_view_change_after_fancybox_close();
            }
        });
    }
      
    //The Code below is required if user needs to select or unselect all records by ticking one checkbox.
    //This code also changes a view of control buttons (delete and edit). The control buttons are getting enabled and disabled.
    //The form of record below is required for a proper work of records loaded by ajax. 
    //Can't use a form like $( "#keywords_all_items_select" ).click(function() {...
    $(document).on("click", "#keywords_all_items_select", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var all_checkboxes_select = document.querySelector('#keywords_all_items_select_wrapper');
        var button_edit = document.querySelector('#keywords_button_edit');
        var button_delete = document.querySelector('#keywords_button_delete');
        if ($(this).is(':checked')) {
            all_checkboxes_select.title = all_checkboxes_select.dataset.unselect;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            button_delete.classList.remove('admin-panel-keywords-edit-delete-control-button-disabled');
            button_delete.classList.add('admin-panel-keywords-edit-delete-control-button-enabled');
            button_delete.removeAttribute('disabled');
            //Without the check below in case user selects only one users and then selects all, button edit still will be active.
            if (all_checkboxes.length > 1) {
                button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');
                button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
                button_edit.setAttribute('disabled', '');
            }
            //In case there is only one element in the list, still both button should be activated.
            if (all_checkboxes.length === 1) {
                button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-disabled');
                button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-enabled');
                button_edit.removeAttribute('disabled');
            }
        } else {
             all_checkboxes_select.title = all_checkboxes_select.dataset.select;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            //We need to have to disable edit button as well in case there is only one element in the list.
            button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');            
            button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');            
            button_delete.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        }
    });
    
    //The Code below is making some changes when user is checking separate checkboxes.
    //This way control buttons (delete and edit) view is getting changed. The control buttons are getting enabled and disabled.
    //The form of record below is required for a proper work of records loaded by ajax. 
    //Can't use a form like $( "#keywords_all_items_select" ).click(function() {...
    $(document).on("click", ".admin-panel-keywords-keywords-checkbox", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var all_checkboxes_select = document.querySelector('#keywords_all_items_select');
        var all_checkboxes_select_wrapper = document.querySelector('#keywords_all_items_select_wrapper');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //In case we check all boxes, "Select All" box will be checked automatically.
        //In case user checked the box "Select All" and then untick one of the checkboxes,
        //"Select All" checkbox will be unticked automatically.
        if (all_checkboxes.length === selected_checkbox.length) {
            all_checkboxes_select.checked = true;
            all_checkboxes_select_wrapper.title = all_checkboxes_select_wrapper.dataset.unselect;
        } else {
            all_checkboxes_select.checked = false;
            all_checkboxes_select_wrapper.title = all_checkboxes_select_wrapper.dataset.select;
        }
              
        var button_edit = document.querySelector('#keywords_button_edit');
        var button_delete = document.querySelector('#keywords_button_delete');
        if (selected_checkbox.length === 1) {
            button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-disabled');
            button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-enabled');
            button_edit.removeAttribute('disabled');
            button_delete.classList.remove('admin-panel-keywords-edit-delete-control-button-disabled');
            button_delete.classList.add('admin-panel-keywords-edit-delete-control-button-enabled');
            button_delete.removeAttribute('disabled');
        } else if (selected_checkbox.length === 0) {
            button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');
            button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');
            button_delete.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        } else if (selected_checkbox.length > 1) {
            button_edit.classList.remove('admin-panel-keywords-edit-delete-control-button-enabled');
            button_edit.classList.add('admin-panel-keywords-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
        }
     });
    
    //Sorting by keyword, text, section, creation date and time, update date and time.
    //There will be two sort modes normal and for search.
    $(document).on("click", ".sort", function() {
        if ($(this).data('search_is_on') === 0) {
            keywords_sort($(this).attr('id'));
        } else {
            keyword_search(make_search_url(), $("#keyword_search").val(), 1, $(this).attr('id'), $(this).data('sorting_mode'));
        }
    });
    
    //The function below is making a link to do sorting and going to it.
    function keywords_sort(sorting_method) {
        var checkbox = document.querySelector('.admin-panel-keywords-keywords-checkbox');
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var current_sorting_method = document.querySelector('#'+sorting_method);
        //href equals to url.
        window.location.href = ((checkbox.dataset.localization === "en") ? "" : 
                                 "/ru")+"/admin/keywords/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
    }
    
    //++++++++++++++++++++++Keyword Search.++++++++++++++++++++++
    //This function is required for localization application.
    function make_search_url() {
        var url_for_search_without_localization = "/admin/keywords/search";
        var localization = $("#keyword_search_button").data('localization');
        var url_for_search = (localization === 'en') ? url_for_search_without_localization : "/"+localization+url_for_search_without_localization;
        
        return url_for_search;
    }
    
    $( "#keyword_search_button" ).click(function() {      
        keyword_search(make_search_url(), $("#keyword_search").val(), 1);
    });   
    
    //This event needs to be done like below ($(document).on("click", ...), because due to ajax usage it can't be done like a normal event.
    $(document).on("click", ".turn-page", function() {
        var current_sorting_method_element = document.querySelector('.admin-panel-keywords-keywords-header-caret-used');      
        var go_to_page_number = $(this).data('page');
        
        if (($(this).attr('id')) === "previous_page") {
            go_to_page_number = go_to_page_number - 1;
        } else if (($(this).attr('id')) === "next_page") {
            go_to_page_number = go_to_page_number + 1;
        }
        
        keyword_search(make_search_url(), $("#keyword_search").val(), go_to_page_number, current_sorting_method_element.id, 
                       (current_sorting_method_element.dataset.sorting_mode === "desc") ? "asc" : "desc");
    });
    
    //The function below is calling search function.
    //The last two parameters we have to pass separately, because depending on whether a user is going two swicth within
    //different sorting modes or turn the pages, needs to be applied current sorting mode (asc or desc) or opposite one.
    function keyword_search(url, find_keywords_by_text, page_number, sorting_method = null, sorting_mode = null) {
        if (sorting_method === null || sorting_mode === null) {
            var sorting_method_and_mode = null;
        } else {
            var sorting_method_and_mode = sorting_method+"_"+sorting_mode;
        }
        $.ajax({
                type: "POST",
                url: url,
                data: {find_keywords_by_text: find_keywords_by_text, page_number: page_number, sorting_mode: sorting_method_and_mode},
                success:function(data) {
                    //Title search needs to be added only once, for that reason need to check if element with class "admin-panel-search-title" exists.
                    //Need to add one only if it doesn't exists, otherwise that element will be added every single time when user press "Search" button and
                    //multiple titles will be displayed.
                    if ($(".admin-panel-search-title").length === 0) {
                        var previous_element = document.querySelector('.admin-panel-keywords-search');
                        previous_element.insertAdjacentHTML("afterend", "<div class='admin-panel-search-title'>"+data.title+"</div>");
                    }
                    
                    $('.admin-panel-keywords-control-buttons').html(data.control_buttons);
                    $('.admin-panel-keywords-content').html(data.content);
                }
            });
    }
    
    //The code below is required to make search working when a user is pressing Enter.
    // Get the input field
    var input = document.getElementById("keyword_search");
    
    // Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("keyword_search_button").click();
        }
    });
    
    //++++++++++++++++++++++End of Keyword Search.++++++++++++++++++++++
});

/*--------------------------------------------------------*/