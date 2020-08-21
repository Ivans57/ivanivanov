/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$( document ).ready(function() {
    var form = document.getElementById('admin_panel_create_edit_entity_form');
    var textarea = document.getElementById('article_create_edit_text_area');
    
    sceditor.create(textarea, {
        format: 'bbcode',
        style: '/sceditor-2.1.3/minified/themes/content/default.min.css',
        toolbar: "bold,italic,underline,strike|subscript,superscript|left,center,right,justify|font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|table|code,quote|image|email,link,unlink|emoticon|date,time|maximize,source",
        locale: form.dataset.localization
    });
});