/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums*/

$( document ).ready(function() {
    //Need the few lines below, otherwise POST requests for ajax won't work.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //Making list of all elements with our class.
    var add_album_buttons = document.querySelectorAll('.admin-panel-albums-add-album-button');
    var add_album_links = document.querySelectorAll('.admin-panel-albums-add-album-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_album_buttons.length; i++) {
        clickAddAlbumButton(add_album_buttons[i], add_album_links[i]);
    }
    function clickAddAlbumButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-albums-add-album-button');
            button.classList.add('admin-panel-albums-add-album-button-pressed');
            link.classList.remove('admin-panel-albums-add-album-button-link');
            link.classList.add('admin-panel-albums-add-album-button-link-pressed');
        });
    }

    //Making list of all elements with our class.
    var add_picture_buttons = document.querySelectorAll('.admin-panel-albums-add-picture-button');
    var add_picture_links = document.querySelectorAll('.admin-panel-albums-add-picture-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < add_picture_buttons.length; i++) {
        clickAddPictureButton(add_picture_buttons[i], add_picture_links[i]);
    }
    function clickAddPictureButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-albums-add-picture-button');
            button.classList.add('admin-panel-albums-add-picture-button-pressed');
            link.classList.remove('admin-panel-albums-add-picture-button-link');
            link.classList.add('admin-panel-albums-add-picture-button-link-pressed');
        });
    }

    var albums_control_buttons = document.querySelectorAll('.admin-panel-albums-pictures-and-albums-control-button');
    //var folder_links = document.querySelectorAll('.admin-panel-albums-picture-and-album-control-button-link');    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < albums_control_buttons.length; i++) {
        clickAlbumButton(albums_control_buttons[i]);
    }
    function clickAlbumButton(folder_button) {
        folder_button.addEventListener('click', function() {
            folder_button.classList.remove('admin-panel-albums-pictures-and-albums-control-button');
            folder_button.classList.add('admin-panel-albums-pictures-and-albums-control-button-pressed');
        });
    }
    
    //We need this script to open new Album create page in fancy box window.
    $(".admin-panel-albums-add-album-button-link").fancybox({
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
            var button = document.querySelector('.admin-panel-albums-add-album-button-pressed');
            var link = document.querySelector('.admin-panel-albums-add-album-button-link-pressed');

            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-albums-add-album-button-pressed');
                button.classList.add('admin-panel-albums-add-album-button');
                link.classList.remove('admin-panel-albums-add-album-button-link-pressed');
                link.classList.add('admin-panel-albums-add-album-button-link');
            }
        }
    });

    //We need this script to open new Picture create page in fancy box window.
    $(".admin-panel-albums-add-picture-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '355px',
                    'height' : '545px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            var button = document.querySelector('.admin-panel-albums-add-picture-button-pressed');
            var link = document.querySelector('.admin-panel-albums-add-picture-button-link-pressed');
            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-albums-add-picture-button-pressed');
                button.classList.add('admin-panel-albums-add-picture-button');
                link.classList.remove('admin-panel-albums-add-picture-button-link-pressed');
                link.classList.add('admin-panel-albums-add-picture-button-link');
            }
        }
    });
  
    function albums_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var control_button = document.querySelector('.admin-panel-albums-pictures-and-albums-control-button-pressed');
        unclickButton(control_button);

        function unclickButton(button/*, link*/) {
            button.classList.remove('admin-panel-albums-pictures-and-albums-control-button-pressed');
            button.classList.add('admin-panel-albums-pictures-and-albums-control-button');
        }
    }
    
    $( "#albums_button_edit" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-albums-picture-and-album-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, becuase we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            if (selected_checkbox[0].dataset.entity_type === "directory") {
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/albums/'+selected_checkbox[0].dataset.keyword+'/edit/'+selected_checkbox[0].dataset.parent_keyword;
                edit_album(url, '440px');
            } else {
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/pictures/'+selected_checkbox[0].dataset.keyword+'/edit/'+selected_checkbox[0].dataset.parent_keyword;
                edit_album(url, '545px');
            }
        }
    });
    
    function edit_album(url, height) {
        //We need this script to open existing Album edit page in fancy box window.
        //Also we need this script to open existing Picture edit page in fancy box window.
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '355px',
                    'height' : height,//'440px',
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                albums_control_button_view_change_after_fancybox_close();
            }
        });
    }
    
    $( "#albums_button_delete" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-albums-picture-and-album-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items entity type (directory or file) and keyword.
        
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
            var url = localization+'/admin/albums/'+selected_checkbox_data;  
            
            //Delete window will have different heights depends on what entites and how many of them are in there.
            var window_height = get_delete_album_and_picture_window_height(directories, files, localization);
            delete_album_or_picture(url, window_height);
        }
    });
    
    //This function is required to adjust delete window's height.
    //Delete window will have different heights depends on what entites and how many of them are in there.
    function get_delete_album_and_picture_window_height(directories, files, localization) {
        var window_height;
        //For english localization localization variable should be empty, because it is used for making links.
        if ((directories.length === 1) && (files.length === 0) && (localization === "")) {//en
            //For one directory only, english localization.
            window_height = '250px';
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
            window_height = '275px';
        } else if ((directories.length > 1) && (files.length === 0) && (localization === "/ru")) {
            //For many directories only, russian localization.
            window_height = '275px';
        } else if ((directories.length === 0) && (files.length === 1) && (localization === "/ru")) {
            //For one file only, russian localization.
            window_height = '205px';
        } else if ((directories.length === 0) && (files.length > 1) && (localization === "/ru")) {
            //For many files only, russian localization.
            window_height = '205px';
        } else if ((directories.length > 0) && (files.length > 0) && (localization === "/ru")) {
            //For both directories and files only, russian localization.
            window_height = '300px';
        }
        return window_height;    
    }
    
    //We need this script to open Album or Picture delete page in fancy box window.
    function delete_album_or_picture(url, window_height) {
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
                albums_control_button_view_change_after_fancybox_close();
            }
        });
    }
    
    //The Code below is required if user needs to select or unselect all records by ticking one checkbox.
    //This code also changes a view of control buttons (delete and edit). The control buttons are getting enabled and disabled.
    $('#albums_all_items_select').click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-albums-picture-and-album-checkbox');
        var all_checkboxes_select = document.querySelector('#albums_all_items_select_wrapper');
        var button_edit = document.querySelector('#albums_button_edit');
        var button_delete = document.querySelector('#albums_button_delete');
        if ($(this).is(':checked')) {
            all_checkboxes_select.title = all_checkboxes_select.dataset.unselect;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            button_delete.classList.remove('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_delete.classList.add('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_delete.removeAttribute('disabled');
            //In case there is only one element in the list, still both button should be activated.
            if (all_checkboxes.length === 1) {
                button_edit.classList.remove('admin-panel-albums-pictures-and-albums-control-button-disabled');
                button_edit.classList.add('admin-panel-albums-pictures-and-albums-control-button-enabled');
                button_edit.removeAttribute('disabled');
            }
        } else {
             all_checkboxes_select.title = all_checkboxes_select.dataset.select;
            all_checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            //We need to have to disable edit button as well in case there is only one element in the list.
            button_edit.classList.remove('admin-panel-albums-pictures-and-albums-control-button-enabled');            
            button_edit.classList.add('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-albums-pictures-and-albums-control-button-enabled');            
            button_delete.classList.add('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        }
    });
    
    //The Code below is making some changes when user is checking separate checkboxes.
    //This way control buttons (delete and edit) view is getting changed. The control buttons are getting enabled and disabled.
    $('.admin-panel-albums-picture-and-album-checkbox').click(function() {
        var button_edit = document.querySelector('#albums_button_edit');
        var button_delete = document.querySelector('#albums_button_delete');
        var all_checkboxes = document.querySelectorAll('.admin-panel-albums-picture-and-album-checkbox');
        var all_checkboxes_select = document.querySelector('#albums_all_items_select');
        var all_checkboxes_select_wrapper = document.querySelector('#albums_all_items_select_wrapper');
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
        
        if (selected_checkbox.length === 1) {
            button_edit.classList.remove('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_edit.classList.add('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_edit.removeAttribute('disabled');
            button_delete.classList.remove('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_delete.classList.add('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_delete.removeAttribute('disabled');
        } else if (selected_checkbox.length === 0) {
            button_edit.classList.remove('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_edit.classList.add('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_edit.setAttribute('disabled', '');
            button_delete.classList.remove('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_delete.classList.add('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_delete.setAttribute('disabled', '');
        } else if (selected_checkbox.length > 1) {
            button_edit.classList.remove('admin-panel-albums-pictures-and-albums-control-button-enabled');
            button_edit.classList.add('admin-panel-albums-pictures-and-albums-control-button-disabled');
            button_edit.setAttribute('disabled', '');
        }
     });
});

/*--------------------------------------------------------*/

/*Scripts for Admin Panel Articles*/

$( document ).ready(function() {
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

    //Making list of all elements with selected class.
    var articles_control_buttons = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < articles_control_buttons.length; i++) {
        clickFolderButton(articles_control_buttons[i]);
    }    
    function clickFolderButton(control_button) {
        control_button.addEventListener('click', function() {
            control_button.classList.remove('admin-panel-articles-article-and-folder-control-button');
            control_button.classList.add('admin-panel-articles-article-and-folder-control-button-pressed');
        });
    }

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
        var button = document.querySelector('.admin-panel-articles-article-and-folder-control-button-pressed');
        unclickButton(button);

        function unclickButton(button) {
            button.classList.remove('admin-panel-articles-article-and-folder-control-button-pressed');
            button.classList.add('admin-panel-articles-article-and-folder-control-button');
        }
    }
       
    $( "#articles_button_edit" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        //We need to make a condition below, becuase we can edit only one item at the same time.
        if (selected_checkbox.length === 1) {
            if (selected_checkbox[0].dataset.entity_type === "directory") {
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/articles/'+selected_checkbox[0].dataset.keyword+'/edit/'+selected_checkbox[0].dataset.parent_keyword;
                edit_folder(url);
            } else {
                var localization = (selected_checkbox[0].dataset.localization === "en") ? "" : "/ru";
                var url = localization+'/admin/article/'+selected_checkbox[0].dataset.parent_keyword+'/edit/'+selected_checkbox[0].dataset.keyword;
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
    
    $( "#articles_button_delete" ).click(function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items entity type (directory or file) and keyword.
        
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
            var url = localization+'/admin/articles/delete/'+selected_checkbox_data;  
            
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
    $('#articles_all_items_select').click(function() {
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
    $('.admin-panel-articles-article-and-folder-checkbox').click(function() {
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

    //Sorting by name.
    $( "#articles_sort_by_name" ).click(function() {
        var current_element = document.querySelector('#articles_sort_by_name');
        articles_sort("articles_sort_by_name", current_element.dataset.is_level_zero, current_element.dataset.parent_keyword);
    });
    
    //Sorting by creation date and time.
    $( "#articles_sort_by_creation" ).click(function() {
        var current_element = document.querySelector('#articles_sort_by_name');
        articles_sort("articles_sort_by_creation", current_element.dataset.is_level_zero, current_element.dataset.parent_keyword);
    });
    
    //Sorting by update date and time.
    $( "#articles_sort_by_update" ).click(function() {
        var current_element = document.querySelector('#articles_sort_by_name');
        articles_sort("articles_sort_by_update", current_element.dataset.is_level_zero, current_element.dataset.parent_keyword);
    });
    
    //The function below is making a link to do sorting and going to it.
    //For elements on level 0 and for different level elements will be different links, for that reason we need that parameter.
    function articles_sort(sorting_method, is_level_zero, keyword) {
        var checkbox = document.querySelector('.admin-panel-articles-article-and-folder-checkbox');
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var localization = (checkbox.dataset.localization === "en") ? "" : "/ru";
        var current_sorting_method = document.querySelector('#'+sorting_method);
        if (is_level_zero === "1") {
            var url = localization+"/admin/articles/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
        } else {//admin/articles/{keyword}/page/{page}
            var url = localization+"/admin/articles/"+keyword+"/page/1/"+current_sorting_method.id+"_"+current_sorting_method.dataset.sorting_mode;
        }
        window.location.href = url;
    }

/*--------------------------------------------------------*/

/*Scripts for Admin Panel Keywords*/

$( document ).ready(function() {
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
});

/*--------------------------------------------------------*/