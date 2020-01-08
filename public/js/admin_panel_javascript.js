/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-albums-add-picture-album-button');
    var links = document.querySelectorAll('.admin-panel-albums-add-picture-album-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i], links[i]);
    }
    function clickButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-albums-add-picture-album-button');
            button.classList.add('admin-panel-albums-add-picture-album-button-pressed');
            link.classList.remove('admin-panel-albums-add-picture-album-button-link');
            link.classList.add('admin-panel-albums-add-picture-album-button-link-pressed');
        });
    }
});

$( document ).ready(function() {
    //making list of all elements with our class.
    var folder_buttons = document.querySelectorAll('.admin-panel-albums-picture-and-album-control-button');
    var folder_links = document.querySelectorAll('.admin-panel-albums-picture-and-album-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < folder_buttons.length; i++) {
        clickFolderButton(folder_buttons[i], folder_links[i]);
    }

    
    function clickFolderButton(folder_button, folder_link) {
        folder_button.addEventListener('click', function() {
            folder_button.classList.remove('admin-panel-albums-picture-and-album-control-button');
            folder_button.classList.add('admin-panel-albums-picture-and-album-control-button-pressed');
            folder_link.classList.remove('admin-panel-albums-picture-and-album-control-button-link');
            folder_link.classList.add('admin-panel-albums-picture-and-album-control-button-link-pressed');
        });
    }
      
});

//We need this script to open new Album create page in fancy box window
$( document ).ready(function() {
    $(".admin-panel-albums-add-picture-album-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '330px',
                    'height' : '420px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            var button = document.querySelector('.admin-panel-albums-add-picture-album-button-pressed');
            var link = document.querySelector('.admin-panel-albums-add-picture-album-button-link-pressed');

            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-albums-add-picture-album-button-pressed');
                button.classList.add('admin-panel-albums-add-picture-album-button');
                link.classList.remove('admin-panel-albums-add-picture-album-button-link-pressed');
                link.classList.add('admin-panel-albums-add-picture-album-button-link');
            }
        }
    });
});

/*--------------------------------------------------------*/

/*Scripts for Admin Panel Articles*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-articles-add-article-folder-button');
    var links = document.querySelectorAll('.admin-panel-articles-add-article-folder-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i], links[i]);
    }
    function clickButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-articles-add-article-folder-button');
            button.classList.add('admin-panel-articles-add-article-folder-button-pressed');
            link.classList.remove('admin-panel-articles-add-article-folder-button-link');
            link.classList.add('admin-panel-articles-add-article-folder-button-link-pressed');
        });
    }
});

$( document ).ready(function() {
    //making list of all elements with our class.
    var folder_buttons = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button');
    var folder_links = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button-link');
    
    var article_buttons = document.querySelectorAll('.admin-panel-articles-article-control-button');
    var article_links = document.querySelectorAll('.admin-panel-articles-article-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < folder_buttons.length; i++) {
        clickFolderButton(folder_buttons[i], folder_links[i]);
    }
    
    for (var i = 0; i < article_buttons.length; i++) {
        clickArticleButton(article_buttons[i], article_links[i]);
    }
    
    function clickFolderButton(folder_button, folder_link) {
        folder_button.addEventListener('click', function() {
            folder_button.classList.remove('admin-panel-articles-article-and-folder-control-button');
            folder_button.classList.add('admin-panel-articles-article-and-folder-control-button-pressed');
            folder_link.classList.remove('admin-panel-articles-article-and-folder-control-button-link');
            folder_link.classList.add('admin-panel-articles-article-and-folder-control-button-link-pressed');
        });
    }
    
    function clickArticleButton(article_button, article_link) {
        article_button.addEventListener('click', function() {
            article_button.classList.remove('admin-panel-articles-article-control-button');
            article_button.classList.add('admin-panel-articles-article-control-button-pressed');
            article_link.classList.remove('admin-panel-articles-article-control-button-link');
            article_link.classList.add('admin-panel-articles-article-control-button-link-pressed');
        });
    }   
});

/*--------------------------------------------------------*/


/*Scripts for Admin Panel Keywords*/

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-keywords-add-keyword-button');
    var links = document.querySelectorAll('.admin-panel-keywords-add-keyword-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i], links[i]);
    }
    function clickButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-keywords-add-keyword-button');
            button.classList.add('admin-panel-keywords-add-keyword-button-pressed');
            link.classList.remove('admin-panel-keywords-add-keyword-button-link');
            link.classList.add('admin-panel-keywords-add-keyword-button-link-pressed');
        });
    }
});

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-keywords-keyword-control-button');
    var links = document.querySelectorAll('.admin-panel-keywords-keyword-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i], links[i]);
    }
    
    function clickButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-keywords-keyword-control-button');
            button.classList.add('admin-panel-keywords-keyword-control-button-pressed');
            link.classList.remove('admin-panel-keywords-keyword-control-button-link');
            link.classList.add('admin-panel-keywords-keyword-control-button-link-pressed');
        });
    }  
});

//We need this script to open new keyword create page in fancy box window
$( document ).ready(function() {
    $(".admin-panel-keywords-add-keyword-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '330px',
                    'height' : '370px',
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
});

//We need this script to open keyword edit page in fancy box window
$( document ).ready(function() {
    $(".admin-panel-keywords-keyword-edit-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '330px',
                    'height' : '370px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            var button = document.querySelector('.admin-panel-keywords-keyword-control-button-pressed');
            var link = document.querySelector('.admin-panel-keywords-keyword-control-button-link-pressed');

            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-keywords-keyword-control-button-pressed');
                button.classList.add('admin-panel-keywords-keyword-control-button');
                link.classList.remove('admin-panel-keywords-keyword-control-button-link-pressed');
                link.classList.add('admin-panel-keywords-keyword-control-button-link');
            }
        }
    });
});

//We need this script to open keyword delete confirmation page in fancy box window
$( document ).ready(function() {
    $(".admin-panel-keywords-keyword-delete-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '330px',
                    'height' : '170px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            //We don't need an array here as in previous examples, because there will be
            //always only one pressed element.
            var button = document.querySelector('.admin-panel-keywords-keyword-control-button-pressed');
            var link = document.querySelector('.admin-panel-keywords-keyword-control-button-link-pressed');

            unclickButton(button, link);

            function unclickButton(button, link) {
                button.classList.remove('admin-panel-keywords-keyword-control-button-pressed');
                button.classList.add('admin-panel-keywords-keyword-control-button');
                link.classList.remove('admin-panel-keywords-keyword-control-button-link-pressed');
                link.classList.add('admin-panel-keywords-keyword-control-button-link');
            }
        }
    });
});

/*--------------------------------------------------------*/