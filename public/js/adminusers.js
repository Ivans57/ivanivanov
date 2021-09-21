/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Users*/

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
    var add_user_buttons = document.querySelectorAll('.admin-panel-users-add-user-button');
    var add_user_links = document.querySelectorAll('.admin-panel-users-add-user-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_user_buttons.length; i++) {
        clickAddUserButton(add_user_buttons[i], add_user_links[i]);
    }
    function clickAddUserButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-users-add-user-button');
            button.classList.add('admin-panel-users-add-user-button-pressed');
            link.classList.remove('admin-panel-users-add-user-button-link');
            link.classList.add('admin-panel-users-add-user-button-link-pressed');
        });
    }

    //We need this script to open new user create page in fancy box window.
    $(".admin-panel-users-add-user-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '355px',
                    'height' : '520px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new user.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            unclickButton(document.querySelector('.admin-panel-users-add-user-button-pressed'), 
                          document.querySelector('.admin-panel-users-add-user-button-link-pressed'));

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-users-add-user-button-pressed');
                button.classList.add('admin-panel-users-add-user-button');
                link.classList.remove('admin-panel-users-add-user-button-link-pressed');
                link.classList.add('admin-panel-users-add-user-button-link');
            }
        }
    });    
    
    $(document).on("click", ".admin-panel-users-edit-delete-control-button", function() {
        $(this).removeClass("admin-panel-users-edit-delete-control-button").addClass("admin-panel-users-edit-delete-control-button-pressed");
    });
    
    function users_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        unclickButton(document.querySelector('.admin-panel-users-edit-delete-control-button-pressed'));

        function unclickButton(button) {
            button.classList.remove('admin-panel-users-edit-delete-control-button-pressed');
            button.classList.add('admin-panel-users-edit-delete-control-button');
        }
    }
    
    $(document).on("click", "#users_button_edit", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-users-users-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, becuase we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            //Passing url as a parameter.
            edit_user(((selected_checkbox[0].dataset.localization === "en") ? "" : 
                           "/ru")+'/admin/keywords/'+selected_checkbox[0].dataset.keyword+'/edit/');
        }
    });
    
    function edit_user(url) {
        //We need this script to open existing User edit page in fancy box window.
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
            //closing pop up's window without adding a new user.
            afterClose: function() {
                users_control_button_view_change_after_fancybox_close();
            }
        });
    }

    $(document).on("click", "#users_button_delete", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-users-users-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items (users) separated by ";" sign.            
        
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                var keyword = all_checkboxes[i].dataset.keyword+';';
                selected_checkbox_data = selected_checkbox_data+keyword;
            }
        }
        if (selected_checkbox_data.length > 0) {
            //For different localization will be different height.
            //passing url as the first parameter and height as the second parameter.
            delete_users((((all_checkboxes[0].dataset.localization === "en") ? "" : "/ru")+'/admin/keywords/delete/'+selected_checkbox_data), 
                               ((all_checkboxes[0].dataset.localization === "en") ? "170px" : "200px"));
        }
    });

    function delete_users(url, height) {
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
            //closing pop up's window without adding a new user.
            afterClose: function() {
                users_control_button_view_change_after_fancybox_close();
            }
        });
    }
      
    //The Code below is required if user needs to select or unselect all records by ticking one checkbox.
    //This code also changes a view of control buttons (delete and edit). The control buttons are getting enabled and disabled.
    //The form of record below is required for a proper work of records loaded by ajax. 
    //Can't use a form like $( "#users_all_items_select" ).click(function() {...
    $(document).on("click", "#users_all_items_select", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-users-users-checkbox');
        var all_checkboxes_select = document.querySelector('#users_all_items_select_wrapper');
        var button_edit = document.querySelector('#users_button_edit');
        var button_delete = document.querySelector('#users_button_delete');
        if ($(this).is(':checked')) {
            all_checkboxes_select.title = all_checkboxes_select.dataset.unselect;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            button_delete.classList.remove('admin-panel-users-edit-delete-control-button-disabled');
            button_delete.classList.add('admin-panel-users-edit-delete-control-button-enabled');
            button_delete.removeAttribute('disabled');
            //In case there is only one element in the list, still both button should be activated.
            if (all_checkboxes.length === 1) {
                button_edit.classList.remove('admin-panel-users-edit-delete-control-button-disabled');
                button_edit.classList.add('admin-panel-users-edit-delete-control-button-enabled');
                button_edit.removeAttribute('disabled');
            }
        } else {
            all_checkboxes_select.title = all_checkboxes_select.dataset.select;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            //We need to have to disable edit button as well in case there is only one element in the list.
            button_edit.classList.remove('admin-panel-users-edit-delete-control-button-enabled');            
            button_edit.classList.add('admin-panel-users-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-users-edit-delete-control-button-enabled');            
            button_delete.classList.add('admin-panel-users-edit-delete-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        }
    });
    
    //The Code below is making some changes when user is checking separate checkboxes.
    //This way control buttons (delete and edit) view is getting changed. The control buttons are getting enabled and disabled.
    //The form of record below is required for a proper work of records loaded by ajax. 
    //Can't use a form like $( "#users_all_items_select" ).click(function() {...
    $(document).on("click", ".admin-panel-users-users-checkbox", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-users-users-checkbox');
        var all_checkboxes_select = document.querySelector('#users_all_items_select');
        var all_checkboxes_select_wrapper = document.querySelector('#users_all_items_select_wrapper');
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
              
        var button_edit = document.querySelector('#users_button_edit');
        var button_delete = document.querySelector('#users_button_delete');
        if (selected_checkbox.length === 1) {
            button_edit.classList.remove('admin-panel-users-edit-delete-control-button-disabled');
            button_edit.classList.add('admin-panel-users-edit-delete-control-button-enabled');
            button_edit.removeAttribute('disabled');
            button_delete.classList.remove('admin-panel-users-edit-delete-control-button-disabled');
            button_delete.classList.add('admin-panel-users-edit-delete-control-button-enabled');
            button_delete.removeAttribute('disabled');
        } else if (selected_checkbox.length === 0) {
            button_edit.classList.remove('admin-panel-users-edit-delete-control-button-enabled');
            button_edit.classList.add('admin-panel-users-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-users-edit-delete-control-button-enabled');
            button_delete.classList.add('admin-panel-users-edit-delete-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        } else if (selected_checkbox.length > 1) {
            button_edit.classList.remove('admin-panel-users-edit-delete-control-button-enabled');
            button_edit.classList.add('admin-panel-users-edit-delete-control-button-disabled');
            button_edit.setAttribute('disabled', '');
        }
     });
    
    //Sorting by username, email, creation date and time, update date and time, role and status.
    //There will be two sort modes normal and for search.
    $(document).on("click", ".sort", function() {
        users_sort($(this).attr('id'));
    });
    
    //The function below is making a link to do sorting and going to it.
    function users_sort(sorting_method) {
        var checkbox = document.querySelector('.admin-panel-users-users-checkbox');
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var current_sorting_method = document.querySelector('#'+sorting_method);
        //href equals to url.
        window.location.href = ((checkbox.dataset.localization === "en") ? "" : 
                                 "/ru")+"/admin/users/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
    }
});

/*--------------------------------------------------------*/