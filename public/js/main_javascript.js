//If scripts or CSS do not take an effect in Mozilla Firefox Chrome , press Ctrl+F5!

/*Functions for pressed buttons view change.*/

$( "#en-laguage-button" ).click(function() {
    var control = document.querySelector('.website-en-laguage-select-button');
    control.classList.add('website-laguage-select-button-pressed');
});

$( "#rus-laguage-button" ).click(function() {
    var control = document.querySelector('.website-rus-laguage-select-button');
    control.classList.add('website-laguage-select-button-pressed');
});

/*--------------------------------------------------------*/

/*Two functions below are functions for pop-up menu manipulation.*/

$( "#menu-compact-view" ).click(function() {
    var pop_up_menu = document.querySelector('.pop-up-menu');
    pop_up_menu.classList.add('pop-up-menu-activated');
    var website_wrapper = document.querySelector('.website-wrapper');
    website_wrapper.classList.add('website-wrapper-fixed');
    
    /*this part of code (below) we need to make dynamically pop up menu height.
    If we don't do that and make it in css 100% it pop up menu will cover only
     visible part of the screen and if we scroll the pop up menu everything 
     below the bottom border of the screen will be without background color.*/
    var website_wrapper_height = $('#website-wrapper').height();
    $('.pop-up-menu').css('height',website_wrapper_height);
});

$( "#pop-up-menu-close" ).click(function() {
    var pop_up_menu = document.querySelector('.pop-up-menu');
    pop_up_menu.classList.remove('pop-up-menu-activated');
    var website_wrapper = document.querySelector('.website-wrapper');
    website_wrapper.classList.remove('website-wrapper-fixed');
    
});

/*--------------------------------------------------------*/

/*Decorations for compact view menu.*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var controls = document.querySelectorAll('.pop-up-menu-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < controls.length; i++) {
        clickControl(controls[i]);
    }
    function clickControl(control) {
        control.addEventListener('click', function() {
           control.classList.add('pop-up-menu-link-pressed');
        });
}
});

/*--------------------------------------------------------*/

/*Decorations for normal view menu.*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var controls = document.querySelectorAll('.website-menu-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < controls.length; i++) {
        clickControl(controls[i]);
    }
    function clickControl(control) {
        control.addEventListener('click', function() {
           control.classList.add('website-menu-button-pressed');
        });
    }
});

$( "#know-more-button" ).click(function() {
    var control = document.querySelector('.home-know-more-button-link');
    control.classList.add('home-know-more-button-pressed');
});

/*--------------------------------------------------------*/

/*Functions for picture fancyboxes.*/

$( document ).ready(function() {
    $(".ivan_ivanov_main_picture").fancybox({
        protect         : true
    });
    
    $(".article_picture").fancybox({
        protect         : true
    });
    
    $(".album-picture-link").fancybox({
        loop            : false,
        protect         : true,
        transitionEffect	: 'slide',
        transitionDuration : 866,
        wheel : 'auto'
    });
});

/*--------------------------------------------------------*/

/*Functions for album folders.*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var album_folders = document.querySelectorAll('.album-folder');
    var album_folders_titles = document.querySelectorAll('.album-folder-title');
    for (var i = 0; i < album_folders.length; i++) {
        clickControl(album_folders[i], i);
}
    function clickControl(album_folder, album_folder_number) {
        album_folder.addEventListener('click', function() {
           album_folder.classList.add('album-is-chosen-for-body');
           album_folders_titles[album_folder_number].classList.add('album-is-chosen-for-title');
        });
}
});

/*--------------------------------------------------------*/

/*Functions for article folders.*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var article_folders = document.querySelectorAll('.article-folder');
    var article_folders_titles = document.querySelectorAll('.article-folder-title');
    for (var i = 0; i < article_folders.length; i++) {
        clickControl(article_folders[i], i);
}
    function clickControl(article_folder, article_folder_number) {
        article_folder.addEventListener('click', function() {
           article_folder.classList.add('folder-is-chosen-for-body');
           article_folders_titles[article_folder_number].classList.add('folder-is-chosen-for-title');
        });
}
});

/*--------------------------------------------------------*/

/*Albums and Articles Search and sort*/

$( document ).ready(function() {
    
    /*++++++++++++++++++++Search++++++++++++++++++++*/
    
    //We need the following lines to make ajax requests work.
    //There are special tokens used for security. We need to add them in all heads
    //and also ajax should be set up to pass them.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //This function is required for localization application.
    function make_search_url() {
        var url_for_search_without_localization = $("#search_button").data('section')+"/search";
        var localization = $("#search_button").data('localization');
        var url_for_search = (localization === 'en') ? "/"+url_for_search_without_localization : "/"+localization+"/"+url_for_search_without_localization;
        
        return url_for_search;
    }
    
    $("#search_button").click(function() {
        //The fifth parameter will be always 'all', because when searching something again, need to drop all filters and sortings.
        search($("#search_is_on").val(), make_search_url(), $("#search").val(), $("#what_to_search").val(), 1);
    });   
    
    //This event needs to be done like below ($(document).on("click", ...), because due to ajax usage it can't be done like a normal event.
    $(document).on("click", ".turn-page", function() {            
        var go_to_page_number = $(this).data('page');
        
        if (($(this).attr('id')) === "previous_page") {
            go_to_page_number = go_to_page_number - 1;
        } else if (($(this).attr('id')) === "next_page") {
            go_to_page_number = go_to_page_number + 1;
        }       
        //The first parameter will always be "1", because pagination arrows for search will appear only when search mode is on.
        search("1", make_search_url(), $("#search").val(), $("#what_to_search").val(), go_to_page_number, $("#sort").val());
    });
    
    //The function below is calling search function.
    //The last two parameters we have to pass separately, because depending on whether a user is going two swicth within
    //different sorting modes or turn the pages, needs to be applied current sorting mode (asc or desc) or opposite one.
    function search(search_is_on, url, find_by_name, what_to_search, page_number, sorting_method_and_mode = null) {
        $.ajax({
                type: "POST",
                url: url,
                data: {search_is_on: search_is_on, find_by_name: find_by_name, page_number: page_number, 
                       what_to_search: what_to_search, sorting_mode: sorting_method_and_mode},
                success: function(data) {
                    //On some views some elements (divs) won't exist, that's why some checks are required.
                    //In the first case, we still need that div, but because it doesn't exist in folders, need to add it.
                    if ($(".albums-or-articles-title").length) {
                        $('.albums-or-articles-title').html(data.title);
                    } else {
                        //Below I am taking a common class, because this script is universal.
                        var article_container = document.querySelector('.website-main-article');
                        article_container.insertAdjacentHTML("afterbegin", "<div class='albums-or-articles-title'>"+data.title+"</div>");
                    }
                    if ($(".path-panel").length) {
                        $('.path-panel').html(data.path);
                    }
                    $('.albums-or-articles-content').html(data.content);
                }
            });
    }
    
    /*++++++++++++++++++++++++++++++++++++++++++++++*/
    
    /*++Scripts for website Albums and Articles sorting.++*/

    $(document).on('change', '#sort', function() {
        var current_element = document.querySelector('#sort');
        if($("#search_is_on").val() === '0') {
            directories_or_files_sort($(this).val(), current_element);
        } else if($("#search_is_on").val() === '1') {
            search("1", make_search_url(), $("#search").val(), $("#what_to_search").val(), "1", $("#sort").val());
        }
    });

    //The function below is making a link to do sorting and going to it.
    //For elements on level 0 and for different level elements will be different links, for that reason we need that parameter.
    //The last parameter have only included items.
    function directories_or_files_sort(sorting_method, current_element) {
            //If it is an english localization, we don't need to show it, because it is a default localization.
            var localization = (current_element.dataset.localization === "en") ? "" : "/ru";
            var url;
            if (current_element.dataset.is_level_zero === "1") {
                url = localization+"/"+current_element.dataset.section+"/"+sorting_method; 
            } else {
                //We need to get the value below in case user changes the sequince of what entity type to show first and then applies another sorting mode.
                //If we don't pass that value to the function below, changed entity display sequince will be lost after another sorting mode has been applied.
                //But that will be applied only for non level 0 elements.
                var directories_or_files_first = null;
                //The check below is required to check if a parent folder(album) has also articles(pictures) included. If it doesn't have them, 
                //there is no need for the last parameter. If there is only one entity in the folder (album), radio elements don't exist and without
                //the check below, there will be an error.
                if (current_element.dataset.has_files === 'true' && current_element.dataset.has_directories === 'true') {
                    directories_or_files_first = $("#directories_or_files_first").val();
                }//document.querySelector('input[name="directories_or_files_first"]:checked').value
                url = localization+"/"+current_element.dataset.section+"/"+current_element.dataset.parent_keyword+"/page/1/"+
                      sorting_method+"/"+directories_or_files_first;
            }
            window.location.href = url;
    }

    //This function is required to show folders(albums) or articles(pictures) first.
    /*$("input[name='directories_or_files_first']").change(function() {
        var directories_or_files_first_value = $(this).val();
        var element_with_sorting_info = document.querySelector('#sort');

        directories_or_files_first(element_with_sorting_info, directories_or_files_first_value);
    });*/
    
    $(document).on('change', '#directories_or_files_first', function() {
        var directories_or_files_first_value = $(this).val();
        var element_with_sorting_info = document.querySelector('#sort');

        directories_or_files_first(element_with_sorting_info, directories_or_files_first_value);
    });

    function directories_or_files_first(element_with_sorting_info, directories_or_files_first_value) {
        //If it is an english localization, we don't need to show it, because it is a default localization.
        var localization = (element_with_sorting_info.dataset.localization === "en") ? "" : "/ru";

        var url = localization+"/"+element_with_sorting_info.dataset.section+"/"+
                  element_with_sorting_info.dataset.parent_keyword+
                  "/page/1/"+element_with_sorting_info.value+"/"+directories_or_files_first_value;
        window.location.href = url;
    }
    
    /*++++++++++++++++++++++++++++++++++++++++++++++*/
});
    
 /*--------------------------------------------------------*/