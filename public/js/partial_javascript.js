/*For partial view in fancybox window we need to make separate scripts file.
  Otherwise scipts are not working properly.*/
/*We need the script below to make the button submitting forms.
  I can not use submit input in that particular case, because 
  "word-wrap: break-word" doesn't work for submit input. It works
  only for buttons*/
/*We need the script below to control the number of characters in the input field*/
$( document ).ready(function() {
    var form = document.getElementById('admin-panel-create-keyword-form');
    var button_save = document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-button-save');
    var keyword_input =document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-input-keyword');
    var text_input =document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-input-text');
    var notification_container =document.querySelector('.admin-panel-keywords-create-notification-wrapper');
    
    button_save.onclick = function() {
        //Save (Submit) button should close the form only if all inputs have proper values
        if (keyword_input.value === "" || text_input.value === "") {
            
            notification_container.insertAdjacentHTML("beforeend", "<div \n\
            class='admin-panel-keywords-create-notification alert \n\
            alert-danger alert-dismissible' role='alert'>\n\
            <button type='button' class='close' data-dismiss='alert' \n\
            aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
            </button>" + button_save.dataset.message + "</div>");
        } else {
            form.submit();
            //After submitting the form we need to colse the fancy box and reload the page
            if (typeof window.parent.$.fancybox!=='undefined'){
                window.parent.$.fancybox.close();
            }
            parent.location.reload(true);
        }
    };
    
    keyword_input.addEventListener('input', function() {
        if (keyword_input.value.length >= 50) {
            
            notification_container.insertAdjacentHTML("beforeend", "<div \n\
            id='character-amount-notification' \n\
            class='admin-panel-keywords-create-notification alert \n\
            alert-danger alert-dismissible' role='alert'>\n\
            <button type='button' class='close' data-dismiss='alert' \n\
            aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
            </button>" + keyword_input.dataset.message + "</div>");
            keyword_input.addEventListener('input', function() {
                if (keyword_input.value.length < 50) {
                    if (document.contains(document.getElementById("character-amount-notification"))) {
                        document.getElementById("character-amount-notification").remove();
                    }
                }
            });
        }
    }); 
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

function myFunction(keywords) {
    /*alert(keywords);*/
    var keyword_input =document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-input-keyword');
    
    for (var i = 0; i < keywords.length; i++) {
        if (new String(keywords[i]).valueOf().trim() === new String(keyword_input.value).valueOf().trim()) {
        alert('Equals!');
        } else {
        alert('Not equals!');
        }
    }
};

/*$( "#click" ).click(function() {
    var keyword_input =document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-input-keyword');
    //var test = "Hello world";
    
    //alert(keyword_input.dataset.keywords);
    
    var all_keywords = keyword_input.dataset.keywords;
    
    
    
    //if (new String(test).valueOf().trim() === new String(keyword_input.value).valueOf().trim()) {
        //alert('Equals!');
    //} else {
        //alert('Not equals!');
    //}
    
    //alert(keyword_input.value);
});*/