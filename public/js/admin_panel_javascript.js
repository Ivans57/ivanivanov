/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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