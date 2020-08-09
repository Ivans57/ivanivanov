/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$( document ).ready(function() {   
    var textarea = document.getElementById('example');
        sceditor.create(textarea, {
            format: 'bbcode',
            //icons: 'monocons',
            style: '/sceditor-2.1.3/minified/themes/content/default.min.css'
    });
});