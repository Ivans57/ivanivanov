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
    var buttons = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button');
    var links = document.querySelectorAll('.admin-panel-articles-article-and-folder-control-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i], links[i]);
    }
    function clickButton(button, link) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-articles-article-and-folder-control-button');
            button.classList.add('admin-panel-articles-article-and-folder-control-button-pressed');
            link.classList.remove('admin-panel-articles-article-and-folder-control-button-link');
            link.classList.add('admin-panel-articles-article-and-folder-control-button-link-pressed');
        });
    }
});