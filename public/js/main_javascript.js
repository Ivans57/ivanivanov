//If scripts or CSS do not take an effect in Mozilla Firefox Chrome , press Ctrl+F5!

/*Functions for pressed buttons view change:*/

$( "#en-laguage-button" ).click(function() {
    var control = document.querySelector('.website-en-laguage-select-button');
    control.classList.add('website-laguage-select-button-pressed');
});

$( "#rus-laguage-button" ).click(function() {
    var control = document.querySelector('.website-rus-laguage-select-button');
    control.classList.add('website-laguage-select-button-pressed');
});

//Two functions below are functions for pop-up menu manipulation
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

//Decorations for compact view menu
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

//Decorations for normal view menu
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


//Functions for picture fancyboxes

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

//Functions for album folders.

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

//Functions for article folders

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
