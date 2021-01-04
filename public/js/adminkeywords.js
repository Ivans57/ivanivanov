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

    //Making list of all elements with our class.
    var keyword_buttons = document.querySelectorAll('.admin-panel-keywords-keyword-control-button');
    //var keyword_links = document.querySelectorAll('.admin-panel-keywords-keyword-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < keyword_buttons.length; i++) {
        clickKeywordButton(keyword_buttons[i]/*, keyword_links[i]*/);
    }  
    function clickKeywordButton(button) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-keywords-keyword-control-button');
            button.classList.add('admin-panel-keywords-keyword-control-button-pressed');
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
            var button = document.querySelector('.admin-panel-keywords-add-keyword-button-pressed');
            var link = document.querySelector('.admin-panel-keywords-add-keyword-button-link-pressed');

            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-keywords-add-keyword-button-pressed');
                button.classList.add('admin-panel-keywords-add-keyword-button');
                link.classList.remove('admin-panel-keywords-add-keyword-button-link-pressed');
                link.classList.add('admin-panel-keywords-add-keyword-button-link');
            }
        }
    });
    
    //Making list of all elements with selected class.
    var keywords_control_buttons = document.querySelectorAll('.admin-panel-keywords-edit-delete-control-button');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < keywords_control_buttons.length; i++) {
        clickFolderButton(keywords_control_buttons[i]);
    }    
    function clickFolderButton(control_button) {
        control_button.addEventListener('click', function() {
            control_button.classList.remove('admin-panel-keywords-edit-delete-control-button');
            control_button.classList.add('admin-panel-keywords-edit-delete-control-button-pressed');
        });
    }
    
    function keywords_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var button = document.querySelector('.admin-panel-keywords-edit-delete-control-button-pressed');
        unclickButton(button);

        function unclickButton(button) {
            button.classList.remove('admin-panel-keywords-edit-delete-control-button-pressed');
            button.classList.add('admin-panel-keywords-edit-delete-control-button');
        }
    }

    $( "#keywords_button_edit" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, becuase we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
            var url = localization+'/admin/keywords/'+selected_checkbox[0].dataset.keyword+'/edit/';
            edit_keyword(url);
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

    $( "#keywords_button_delete" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-keywords-keywords-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items (keywords) separated by ";" sign.            
        
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                var keyword = all_checkboxes[i].dataset.keyword+';';
                selected_checkbox_data = selected_checkbox_data+keyword;
            }
        }
        if (selected_checkbox_data.length > 0) {
            var localization = (all_checkboxes[0].dataset.localization === "en") ? "" : "/ru";
            //For different localization will be different height.
            var height = (all_checkboxes[0].dataset.localization === "en") ? "170px" : "200px";
            var url = localization+'/admin/keywords/delete/'+selected_checkbox_data;                                                       
            delete_keywords(url, height);
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
    $('#keywords_all_items_select').click(function() {
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
    $('.admin-panel-keywords-keywords-checkbox').click(function() {
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
    
    //Sorting by keyword.
    $( "#keywords_sort_by_keyword" ).click(function() {
        keywords_sort("keywords_sort_by_keyword");
    });
    
    //Sorting by text.
    $( "#keywords_sort_by_text" ).click(function() {
        keywords_sort("keywords_sort_by_text");
    });
    
    //Sorting by section.
    $( "#keywords_sort_by_section" ).click(function() {
        keywords_sort("keywords_sort_by_section");
    });
    
    //Sorting by creation date and time.
    $( "#keywords_sort_by_creation" ).click(function() {
        keywords_sort("keywords_sort_by_creation");
    });
    
    //Sorting by update date and time.
    $( "#keywords_sort_by_update" ).click(function() {
        keywords_sort("keywords_sort_by_update");
    });
    
    //The function below is making a link to do sorting and going to it.
    function keywords_sort(sorting_method) {
        var checkbox = document.querySelector('.admin-panel-keywords-keywords-checkbox');
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var localization = (checkbox.dataset.localization === "en") ? "" : "/ru";
        var current_sorting_method = document.querySelector('#'+sorting_method);
        var url = localization+"/admin/keywords/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
        window.location.href = url;
    }
    
    //++++++++++++++++++++++Keyword Search.++++++++++++++++++++++
    var url_for_search = "/admin/keywords/search";
    
    $( "#keyword_search_button" ).click(function() {
        var find_keywords_by_text = $( "#keyword_search" ).val();      
        keyword_search(url_for_search, find_keywords_by_text, 1);
    });   
    
    //This event needs to be done like below ($(document).on("click", ...), because due to ajax usage it can't be done like a normal event.
    $(document).on("click", ".turn-page", function() {
        var find_keywords_by_text = $( "#keyword_search" ).val();
        var go_to_page_number = $(this).data('page');
        var current_element_id = $(this).attr('id');
        
        if (current_element_id === "previous_page") {
            go_to_page_number = go_to_page_number - 1;
        } else if (current_element_id === "next_page") {
            go_to_page_number = go_to_page_number + 1;
        }
        
        keyword_search(url_for_search, find_keywords_by_text, go_to_page_number);
    });
    
    //The function below is calling search function.
    function keyword_search(url, find_keywords_by_text, page_number) {
        $.ajax({
                type: "POST",
                url: url,
                data: {find_keywords_by_text: find_keywords_by_text, page_number: page_number},
                success:function(data) {
                    $('.admin-panel-keywords-content').html(data.html);
                }
            });
    }
    
    //++++++++++++++++++++++End of Keyword Search.++++++++++++++++++++++
});

/*--------------------------------------------------------*/