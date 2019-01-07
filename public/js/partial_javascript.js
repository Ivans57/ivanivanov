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
            
            /*alert(button_save.dataset.message);*/
            /*notification_container.insertAdjacentHTML("beforeend", button_save.dataset.message+' Hi');*/
            /*alert('Fields are empty!');*/
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
            
            /*alert(keyword_input.dataset.message);*/
            /*notification_container.insertAdjacentHTML("beforeend", keyword_input.dataset.message+' Bye');*/
            notification_container.insertAdjacentHTML("beforeend", "<div \n\
            id='character-amount-notification' \n\
            class='admin-panel-keywords-create-notification alert \n\
            alert-danger alert-dismissible' role='alert'>\n\
            <button type='button' class='close' data-dismiss='alert' \n\
            aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
            </button>" + keyword_input.dataset.message + "</div>");
            keyword_input.addEventListener('input', function() {
                if (keyword_input.value.length < 50) {
                    /*alert('Too many characters!');*/
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

/*$( "#show-test-div" ).click(function() {
    var test_div = document.querySelector('.admin-panel-keywords-create-notification-wrapper');
    
    test_div.insertAdjacentHTML("afterbegin", "<div class='alert alert-danger \n\
    alert-dismissible' role='alert'><button type='button' class='close' \n\
    data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;\n\
    </span></button><strong>Warning!</strong> Better check yourself, \n\
    you're not looking too good.</div>");  
});*/

/*$( document ).ready(function() {
    var button_close = document.querySelector('.admin-panel-keywords-create-notification-close');
    var alert_notification = document.querySelector('.admin-panel-keywords-create-notification');
    
    button_close.onclick = function() {
        alert_notification.classList.remove('admin-panel-keywords-create-notification');
        alert_notification.classList.add('admin-panel-keywords-create-notification-hidden');
    };
});*/