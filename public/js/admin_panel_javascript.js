/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-article-folder-control-button');
    var links = document.querySelectorAll('.admin-panel-article-folder-control-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i]);
    }
    for (var i = 0; i < links.length; i++) {
        clickLink(links[i]);
    }
    function clickButton(button) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-article-folder-control-button');
            button.classList.add('admin-panel-article-folder-control-button-pressed');
        });
    }
    function clickLink(link) {
        link.addEventListener('click', function() {
            link.classList.remove('admin-panel-article-folder-control-button-link');
            link.classList.add('admin-panel-article-folder-control-button-link-pressed');
        });
    }
});

$( document ).ready(function() {
    //making list of all elements with our class.
    var buttons = document.querySelectorAll('.admin-panel-add-article-folder-button');
    var links = document.querySelectorAll('.admin-panel-add-article-folder-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < buttons.length; i++) {
        clickButton(buttons[i]);
    }
    for (var i = 0; i < links.length; i++) {
        clickLink(links[i]);
    }
    function clickButton(button) {
        button.addEventListener('click', function() {
            button.classList.remove('admin-panel-add-article-folder-button');
            button.classList.add('admin-panel-add-article-folder-button-pressed');
        });
    }
    function clickLink(link) {
        link.addEventListener('click', function() {
            link.classList.remove('admin-panel-add-article-folder-button-link');
            link.classList.add('admin-panel-add-article-folder-button-link-pressed');
        });
    }
});