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

        function unclickButton(control_button) {
            control_button.classList.remove('admin-panel-albums-pictures-and-albums-control-button-pressed');
            control_button.classList.add('admin-panel-albums-pictures-and-albums-control-button');
        }
    }
    
    $(document).on("click", "#albums_button_edit", function() {
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
                    'height' : height,
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
    
    $(document).on("click", "#albums_button_delete", function() {
        var all_checkboxes = document.querySelectorAll('.admin-panel-albums-picture-and-album-checkbox');
        var selected_checkbox_data = "";//In this variable we keep items entity type (directory or file) and keyword.
        //The line below is required to get parent album's keyword, which is necessary for correct hide or show invisible elements activation.
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
            var url = localization+'/admin/albums/delete/'+selected_checkbox_data+"/"+parent_keyword_info.dataset.parent_keyword;  
            
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
    $(document).on("click", "#albums_all_items_select", function() {
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
    $(document).on("click", ".admin-panel-albums-picture-and-album-checkbox", function() {
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