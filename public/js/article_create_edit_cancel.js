/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$( document ).ready(function() {   
    var button_cancel = document.getElementById('button_cancel');
    var form = document.getElementById('admin_panel_create_edit_entity_form');
    var parent_keyword = button_cancel.dataset.parent_keyword;
      
    button_cancel.onclick = function() {
        if (form.dataset.localization === "en") {
            window.location.replace("/admin/articles/"+parent_keyword+"/page/1/"+
                                    $('#sorting_show_invisible').val()+"/"+$('#sorting_sorting_mode').val()+
                                    "/"+$('#sorting_folders_or_articles_first').val());
        } else {
            window.location.replace("/ru/admin/articles/"+parent_keyword+"/page/1/"+
                                    $('#sorting_show_invisible').val()+"/"+$('#sorting_sorting_mode').val()+
                                    "/"+$('#sorting_folders_or_articles_first').val());
        }
    };
});

