/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Articles*/

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
    var add_folder_buttons = document.querySelectorAll('.admin-panel-articles-add-folder-button');
    var add_folder_links = document.querySelectorAll('.admin-panel-articles-add-folder-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_folder_buttons.length; i++) {
        clickAddFolderButton(add_folder_buttons[i], add_folder_links[i]);
    }
    function clickAddFolderButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-articles-add-folder-button');
            button.classList.add('admin-panel-articles-add-folder-button-pressed');
            link.classList.remove('admin-panel-articles-add-folder-button-link');
            link.classList.add('admin-panel-articles-add-folder-button-link-pressed');
        });
    }

    var add_article_buttons = document.querySelectorAll('.admin-panel-articles-add-article-button');
    var add_article_links = document.querySelectorAll('.admin-panel-articles-add-article-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_article_buttons.length; i++) {
        clickAddArticleButton(add_article_buttons[i], add_article_links[i]);
    }
    function clickAddArticleButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-articles-add-article-button');
            button.classList.add('admin-panel-articles-add-article-button-pressed');
            link.classList.remove('admin-panel-articles-add-article-button-link');
            link.classList.add('admin-panel-articles-add-article-button-link-pressed');
        });
    }

    $(document).on("click", ".admin-panel-articles-article-and-folder-control-button", function() {
        $(this).removeClass("admin-panel-articles-article-and-folder-control-button").addClass("admin-panel-articles-article-and-folder-control-button-pressed");
    });

    //We need this script to open new Folder create page in fancy box window.
    $(".admin-panel-articles-add-folder-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '355px',
                    'height' : '440px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            var button = document.querySelector('.admin-panel-articles-add-folder-button-pressed');
            var link = document.querySelector('.admin-panel-articles-add-folder-button-link-pressed');
            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-articles-add-folder-button-pressed');
                button.classList.add('admin-panel-articles-add-folder-button');
                link.classList.remove('admin-panel-articles-add-folder-button-link-pressed');
                link.classList.add('admin-panel-articles-add-folder-button-link');
            }
        }
    });
    
    function articles_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var control_button = document.querySelector('.admin-panel-articles-article-and-folder-control-button-pressed');
        unclickButton(control_button);

        function unclickButton(control_button) {
            control_button.classList.remove('admin-panel-articles-article-and-folder-control-button-pressed');
            control_button.classList.add('admin-panel-articles-article-and-folder-control-button');
        }
    }
       
    $(document).on("click", "#articles_button_edit", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var selected_checkbox = [];
        
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, because we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            if (selected_checkbox[0].dataset.entity_type === "directory") {
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/articles/'+selected_checkbox[0].dataset.keyword+'/edit/'+selected_checkbox[0].dataset.parent_keyword+"/"+$("#search_is_on").val();
                edit_folder(url);
            } else {
                //The variable below is required to keep an element with sorting settings.
                var sorting_settings = document.querySelector('#show_only_visible');
                
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/article/'+selected_checkbox[0].dataset.parent_keyword+'/edit/'+
                          selected_checkbox[0].dataset.keyword+"/"+sorting_settings.value+"/"+
                          sorting_settings.dataset.old_sorting_method_and_mode+"/"+sorting_settings.dataset.old_directories_or_files_first;
                window.location.href = url;
            }
        }
    });
    
    function edit_folder(url) {
        //We need this script to open existing Folder edit page in fancy box window.
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '355px',
                    'height' : '440px',
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                articles_control_button_view_change_after_fancybox_close();
            }
        });
    }
    
    $(document).on("click", "#articles_button_delete", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items entity type (directory or file) and keyword.
        //The line below is required to get parent folder's keyword, which is necessary for correct hide or show invisible elements activation.
        var parent_keyword_info = all_checkboxes[0];
        
        //Depends what kind of items are getting deleted, we need to provide windows with different height,
        //because there will be diffrernt legth messages. Some of them shorter, some of them longer and they will
        //require lees or more space in window.      
        var directories = [];
        var files = [];
        
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                var entity_type_and_keyword = all_checkboxes[i].dataset.entity_type+'+'+all_checkboxes[i].dataset.keyword+';';
                selected_checkbox_data = selected_checkbox_data+entity_type_and_keyword;
                //This is required for check to provide proper height window.
                if (all_checkboxes[i].dataset.entity_type === 'directory') {
                    directories.push(all_checkboxes[i].dataset.keyword);
                } else {
                    files.push(all_checkboxes[i].dataset.keyword);
                }
            }
        }        
        if (selected_checkbox_data.length > 0) {
            var localization = (all_checkboxes[0].dataset.localization === "en") ? "" : "/ru";
            var url = localization+'/admin/articles/delete/'+selected_checkbox_data+"/"+parent_keyword_info.dataset.parent_keyword+"/"+$("#search_is_on").val();  
            
            //Delete window will have different heights depends on what entites and how many of them are in there.
            var window_height = get_delete_folder_and_article_window_height(directories, files, localization);                                            
            delete_folder_or_article(url, window_height);
        }
    });
    
    //This function is required to adjust delete window's height.
    //Delete window will have different heights depends on what entites and how many of them are in there.
    function get_delete_folder_and_article_window_height(directories, files, localization) {
        var window_height;
        //For english localization localization variable should be empty, because it is used for making links.
        if ((directories.length === 1) && (files.length === 0) && (localization === "")) {//en
            //For one directory only, english localization.
            window_height = '230px';
        } else if ((directories.length > 1) && (files.length === 0) && (localization === "")) {
            //For many directories only, english localization.
            window_height = '255px';
        } else if ((directories.length === 0) && (files.length === 1) && (localization === "")) {
            //For one file only, english localization.
            window_height = '205px';
        } else if ((directories.length === 0) && (files.length > 1) && (localization === "")) {
            //For many files only, english localization.
            window_height = '205px';
        } else if ((directories.length > 0) && (files.length > 0) && (localization === "")) {
            //For both directories and files only, english localization.
            window_height = '275px';
        //In localization variable before ru is "/", because this part also is used for making links.
        } else if ((directories.length === 1) && (files.length === 0) && (localization === "/ru")) {
            //For one directory only, russian localization.
            window_height = '250px';
        } else if ((directories.length > 1) && (files.length === 0) && (localization === "/ru")) {
            //For many directories only, russian localization.
            window_height = '250px';
        } else if ((directories.length === 0) && (files.length === 1) && (localization === "/ru")) {
            //For one file only, russian localization.
            window_height = '205px';
        } else if ((directories.length === 0) && (files.length > 1) && (localization === "/ru")) {
            //For many files only, russian localization.
            window_height = '205px';
        } else if ((directories.length > 0) && (files.length > 0) && (localization === "/ru")) {
            //For both directories and files only, russian localization.
            window_height = '270px';
        }
        return window_height;    
    }
      
    function delete_folder_or_article(url, window_height) {
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '380px',
                    'height' : window_height,
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                articles_control_button_view_change_after_fancybox_close();
            }
        });
    }
    
    //The Code below is required if user needs to select or unselect all records by ticking one checkbox.
    //This code also changes a view of control buttons (delete and edit). The control buttons are getting enabled and disabled.
    $(document).on("click", "#articles_all_items_select", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var all_checkboxes_select = document.querySelector('#articles_all_items_select_wrapper');
        var button_edit = document.querySelector('#articles_button_edit');
        var button_delete = document.querySelector('#articles_button_delete');
        if ($(this).is(':checked')) {
            all_checkboxes_select.title = all_checkboxes_select.dataset.unselect;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            button_delete.classList.remove('admin-panel-articles-article-and-folder-control-button-disabled');
            button_delete.classList.add('admin-panel-articles-article-and-folder-control-button-enabled');
            button_delete.removeAttribute('disabled');
            //In case there is only one element in the list, still both button should be activated.
            if (all_checkboxes.length === 1) {
                button_edit.classList.remove('admin-panel-articles-article-and-folder-control-button-disabled');
                button_edit.classList.add('admin-panel-articles-article-and-folder-control-button-enabled');
                button_edit.removeAttribute('disabled');
            }
        } else {
            all_checkboxes_select.title = all_checkboxes_select.dataset.select;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            //We need to have to disable edit button as well in case there is only one element in the list.
            button_edit.classList.remove('admin-panel-articles-article-and-folder-control-button-enabled');            
            button_edit.classList.add('admin-panel-articles-article-and-folder-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-articles-article-and-folder-control-button-enabled');            
            button_delete.classList.add('admin-panel-articles-article-and-folder-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        }
    });
    
    //The Code below is making some changes when user is checking separate checkboxes.
    //This way control buttons (delete and edit) view is getting changed. The control buttons are getting enabled and disabled.
    //$('.admin-panel-articles-article-and-folder-checkbox').click(function() {
    $(document).on("click", ".admin-panel-articles-article-and-folder-checkbox", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var all_checkboxes_select = document.querySelector('#articles_all_items_select');
        var all_checkboxes_select_wrapper = document.querySelector('#articles_all_items_select_wrapper');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //In case we check all boxes, "Select All" box will be checked automatically.
        //In case user checked the box "Select All" and then untick one of the checkboxes,
        //"Select All" checkbox will be unticked automatically
        if (all_checkboxes.length === selected_checkbox.length) {
            all_checkboxes_select.checked = true;
            all_checkboxes_select_wrapper.title = all_checkboxes_select_wrapper.dataset.unselect;
        } else {
            all_checkboxes_select.checked = false;
            all_checkboxes_select_wrapper.title = all_checkboxes_select_wrapper.dataset.select;
        }
              
        var button_edit = document.querySelector('#articles_button_edit');
        var button_delete = document.querySelector('#articles_button_delete');
        if (selected_checkbox.length === 1) {
            button_edit.classList.remove('admin-panel-articles-article-and-folder-control-button-disabled');
            button_edit.classList.add('admin-panel-articles-article-and-folder-control-button-enabled');
            button_edit.removeAttribute('disabled');
            button_delete.classList.remove('admin-panel-articles-article-and-folder-control-button-disabled');
            button_delete.classList.add('admin-panel-articles-article-and-folder-control-button-enabled');
            button_delete.removeAttribute('disabled');
        } else if (selected_checkbox.length === 0) {
            button_edit.classList.remove('admin-panel-articles-article-and-folder-control-button-enabled');
            button_edit.classList.add('admin-panel-articles-article-and-folder-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-articles-article-and-folder-control-button-enabled');
            button_delete.classList.add('admin-panel-articles-article-and-folder-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        } else if (selected_checkbox.length > 1) {
            button_edit.classList.remove('admin-panel-articles-article-and-folder-control-button-enabled');
            button_edit.classList.add('admin-panel-articles-article-and-folder-control-button-disabled');
            button_edit.setAttribute('disabled', '');
        }
     });
});

/*--------------------------------------------------------*/