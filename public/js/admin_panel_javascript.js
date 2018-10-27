/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
    //making list of all elements with our class.
    var controls = document.querySelectorAll('.admin-panel-article-folder-control-button-link');
    //getting through the array of elements and applying required function
    //for all of them. We don't need these elements id anymore.
    for (var i = 0; i < controls.length; i++) {
        clickControl(controls[i]);
}
    function clickControl(control) {
        control.addEventListener('click', function() {
           control.classList.add('admin-panel-article-folder-control-button-pressed');
        });
}
});

