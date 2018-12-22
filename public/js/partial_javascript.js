/*For partial view in fancybox window we need to make separate scripts file.
  Otherwise scipts are not working properly.*/
/*We need the script below to make the button submitting forms.
  I can not use submit input in that particular case, because 
  "word-wrap: break-word" doesn't work for submit input. It works
  only for buttons*/
$( document ).ready(function() {
    var form = document.getElementById('admin-panel-create-keyword-form');
    var button_save = document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-button-save');
    
    button_save.onclick = function() {
        form.submit();
        //After submitting the form we need to colse the fancy box and reload the page
        if (typeof window.parent.$.fancybox!=='undefined'){
            window.parent.$.fancybox.close();
        }
        parent.location.reload(true); 
    };
});

//We need the script below to make a button closing a Fancy Box.
$( document ).ready(function() {
    var button_cancel = document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-button-cancel');
    
    button_cancel.onclick = function() {
        if (typeof window.parent.$.fancybox!=='undefined'){
           window.parent.$.fancybox.close();
        }
    };
});