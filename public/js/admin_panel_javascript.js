/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*Scripts for Admin Panel Albums*/

$( document ).ready(function() {
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
  
    function control_button_view_change_after_fancybox_close(){
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var control_button = document.querySelector('.admin-panel-albums-picture-and-album-control-button-pressed');
        var control_link = document.querySelector('.admin-panel-albums-picture-and-album-control-button-link-pressed');

        unclickButton(control_button, control_link);

        function unclickButton(button, link) {
            button.classList.remove('admin-panel-albums-picture-and-album-control-button-pressed');
            button.classList.add('admin-panel-albums-picture-and-album-control-button');
            link.classList.remove('admin-panel-albums-picture-and-album-control-button-link-pressed');
            link.classList.add('admin-panel-albums-picture-and-album-control-button-link');
        }
    }
    
    //We need this script to open existing Album edit page in fancy box window.
    $(".admin-panel-albums-album-control-button-link-edit").fancybox({
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
            control_button_view_change_after_fancybox_close();
        }
    });
    
    //We need this script to open existing Picture edit page in fancy box window.
    $(".admin-panel-albums-picture-control-button-link-edit").fancybox({
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
            control_button_view_change_after_fancybox_close();
        }
    });
    
    //We need this script to open Album delete page in fancy box window.
    $(".admin-panel-albums-album-control-button-link-delete").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '380px',
                    'height' : '265px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            control_button_view_change_after_fancybox_close();
        }
    });
    
    //We need this script to open Album delete page in fancy box window.
    $(".admin-panel-albums-picture-control-button-link-delete").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '380px',
                    'height' : '205px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            control_button_view_change_after_fancybox_close();
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

    //Making list of all elements with our class.
    var control_buttons = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button');
    //var folder_links = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button-link');
    
    //var article_buttons = document.querySelectorAll('.admin-panel-articles-article-control-button');
    //var article_links = document.querySelectorAll('.admin-panel-articles-article-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < control_buttons.length; i++) {
        clickFolderButton(control_buttons[i]/*, folder_links[i]*/);
    }    
    //for (var i = 0; i < article_buttons.length; i++) {
        //clickArticleButton(article_buttons[i]/*, article_links[i]*/);
    //} 
    function clickFolderButton(control_button/*, folder_link*/) {
        control_button.addEventListener('click', function() {
            control_button.classList.remove('admin-panel-articles-article-and-folder-control-button');
            control_button.classList.add('admin-panel-articles-article-and-folder-control-button-pressed');
            //folder_link.classList.remove('admin-panel-articles-article-and-folder-control-button-link');
            //folder_link.classList.add('admin-panel-articles-article-and-folder-control-button-link-pressed');
        });
    }   
    //function clickArticleButton(article_button/*, article_link*/) {
        //article_button.addEventListener('click', function() {
            //article_button.classList.remove('admin-panel-articles-article-control-button');
            //article_button.classList.add('admin-panel-articles-article-control-button-pressed');
            //article_link.classList.remove('admin-panel-articles-article-control-button-link');
            //article_link.classList.add('admin-panel-articles-article-control-button-link-pressed');
        //});
    //}   

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
    
    function control_button_view_change_after_fancybox_close(){
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var button = document.querySelector('.admin-panel-articles-article-and-folder-control-button-pressed');
        //var link = document.querySelector('.admin-panel-articles-article-and-folder-control-button-link-pressed');

        unclickButton(button/*, link*/);

        function unclickButton(button/*, link*/) {
            button.classList.remove('admin-panel-articles-article-and-folder-control-button-pressed');
            button.classList.add('admin-panel-articles-article-and-folder-control-button');
            //link.classList.remove('admin-panel-articles-article-and-folder-control-button-link-pressed');
            //link.classList.add('admin-panel-articles-article-and-folder-control-button-link');
        }
    }
    
    //We need a separate function for article delete control button, becauae articles
    //will have two control buttons instead of three, like another elements, so
    //there are a bit different styles and only delete control button will open a fancy box 
    //and will require to change its view back. That's why function control_button_view_change_after_fancybox_close
    //cannot be applied.
    function article_control_button_view_change_after_fancybox_close(){
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var button = document.querySelector('.admin-panel-articles-article-control-button-pressed');
        //var link = document.querySelector('.admin-panel-articles-article-control-button-link-pressed');

        unclickButton(button/*, link*/);

        function unclickButton(button/*, link*/) {
            button.classList.remove('admin-panel-articles-article-control-button-pressed');
            button.classList.add('admin-panel-articles-article-control-button');
            //link.classList.remove('admin-panel-articles-article-control-button-link-pressed');
            //link.classList.add('admin-panel-articles-article-control-button-link');
        }
    }
    
    //We need this script to open existing Folder edit page in fancy box window.
    /*$("#button_edit").fancybox({
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
            control_button_view_change_after_fancybox_close();
        }
    });*/
    //var test = document.querySelector('.admin-panel-articles-article-and-folder-checkbox');
    //var test = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
    $( "#button_edit" ).click(function() {
        //var test = document.querySelector('.admin-panel-articles-article-and-folder-checkbox');
        //var check = test.length;
        //var test = document.getElementsByClassName(".admin-panel-articles-article-and-folder-checkbox");
        var all_checkboxes = document.querySelectorAll('.admin-panel-articles-article-and-folder-checkbox');
        var selected_checkbox = [];
        for (i = 0; i < all_checkboxes.length; i++) {
            if (all_checkboxes[i].checked === true) {
                selected_checkbox.push(all_checkboxes[i]);
            }
        }
        alert( "Handler for .click() called." );
    });
    
    //We need this script to open Folder delete page in fancy box window.
    $(".admin-panel-articles-folder-control-button-link-delete").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '380px',
                    'height' : '245px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            control_button_view_change_after_fancybox_close();
        }
    });
    
    //We need this script to open Article delete page in fancy box window.
    $(".admin-panel-articles-article-control-button-link-delete").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '380px',
                    'height' : '205px',
                    'margin-bottom' : '200px'
                }
	},
        //Also we will need a function which will recover add button's view after
        //closing pop up's window without adding a new keyword.
        afterClose: function() {
            article_control_button_view_change_after_fancybox_close();
        }
    });
});

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
    var keyword_links = document.querySelectorAll('.admin-panel-keywords-keyword-control-button-link');
    
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < keyword_buttons.length; i++) {
        clickKeywordButton(keyword_buttons[i], keyword_links[i]);
    }  
    function clickKeywordButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-keywords-keyword-control-button');
            button.classList.add('admin-panel-keywords-keyword-control-button-pressed');
            link.classList.remove('admin-panel-keywords-keyword-control-button-link');
            link.classList.add('admin-panel-keywords-keyword-control-button-link-pressed');
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

    //We need this script to open keyword edit page in fancy box window.
    $(".admin-panel-keywords-keyword-edit-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '355px',
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

    //We need this script to open keyword delete confirmation page in fancy box window.
    $(".admin-panel-keywords-keyword-delete-button-link").fancybox({
	toolbar  : false,
	smallBtn : true,
	iframe : {
		preload : false,
                css : {
                    'width' : '380px',
                    'height' : '180px',
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