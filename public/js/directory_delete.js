/*For partial view in fancybox window we need to make separate scripts file.
  Otherwise scipts are not working properly.*/
/*We need the script below to make the button submitting forms.
  I can not use submit input in that particular case, because 
  "word-wrap: break-word" doesn't work for submit input. It works
  only for buttons*/


$( document ).ready(function() {   
    //Few lines below are made for cancel button, which is closing opened window 
    //without deleting anything.
    var button_cancel = document.getElementById('directory_cancel_button');
    
    //We actually don't need the check below, but I let it just in case
    if (button_cancel !== null) {   
        button_cancel.onclick = function() {
            if (typeof window.parent.$.fancybox!=='undefined'){
                window.parent.$.fancybox.close();
            }
        };
    }
});

