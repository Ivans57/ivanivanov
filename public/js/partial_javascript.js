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
    var keyword = keyword_input.value;
    var text_input =document.querySelector('.admin-panel-keywords-create-edit-keyword-controls-input-text');
    var notification_container =document.querySelector('.admin-panel-keywords-create-notification-wrapper');
    //We need an array of keywords to check whether new keyword is unique
    var keywords = JSON.parse(keyword_input.dataset.keywords);
    //We need the variable below to perform a check whether entered keyword is unique.
    //Form should not be closed if the keyords is not unique.
    var keyword_uniqueness = true;
    //Also we need to perform check if keyword does not have any prohibited
    //symbol. Keyword should contatain only latin alphabet letters.
    const keywordPattern = /[a-zA-Z]/;
    //The variable below is required for checking if new keyword matches
    //symbol requirements
    var keyword_test = true;
    
    button_save.onclick = function() {
        
        //Before performing all checks, we need to check if there is something
        //in keyword field. If we don't do that check in case of empty
        //field there will be an error with JavaScript and 
        //all another checks will not work. 
        if(keyword_input.value !== "") {
        //First of all we need to make the first letter 
        //of keyword capital in case user did not make it capital   
        keyword_input.value = keyword_input.value[0].toUpperCase() + keyword_input.value.slice(1);
        
        //We need to assign keyword variable again as there might be some 
        //made by user changes in that field
        keyword = keyword_input.value;
        
        //We need to make a check whether entered keyword is unique
        for (var i = 0; i < keywords.length; i++) {
            if (new String(keywords[i]).valueOf().trim() === new String(keyword_input.value).valueOf().trim()) {
                notification_container.insertAdjacentHTML("beforeend", "<div \n\
                class='admin-panel-keywords-create-notification alert \n\
                alert-danger alert-dismissible' role='alert'>\n\
                <button type='button' class='close' data-dismiss='alert' \n\
                aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
                </button>" + keyword_input.dataset.uniqueness + "</div>");
                keyword_uniqueness = false;
                break;
            }
        }
        
        //We need to make a check for prohibited symbols
        for ( var i = 0; i < keyword.length; i++ ) {
            keyword_test = keywordPattern.test(keyword[i]);
            if(!keyword_test) {
                notification_container.insertAdjacentHTML("beforeend", "<div \n\
                class='admin-panel-keywords-create-notification alert \n\
                alert-danger alert-dismissible' role='alert'>\n\
                <button type='button' class='close' data-dismiss='alert' \n\
                aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
                </button>" + keyword_input.dataset.symbols + "</div>");
                break;
            }
        }
        
        //New Keyword should not have any space. It should be one word. 
        //We are doing that check below
        var keyword_space_test = keyword_input.value.split(" ");
        
        if (keyword_space_test.length > 1) {
            notification_container.insertAdjacentHTML("beforeend", "<div \n\
            class='admin-panel-keywords-create-notification alert \n\
            alert-danger alert-dismissible' role='alert'>\n\
            <button type='button' class='close' data-dismiss='alert' \n\
            aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
            </button>" + keyword_input.dataset.spaces + "</div>");
        }
        
        }
        
        //Save (Submit) button should close the form only if all inputs have proper values
        if (keyword_input.value === "" || text_input.value === "") {
            notification_container.insertAdjacentHTML("beforeend", "<div \n\
            class='admin-panel-keywords-create-notification alert \n\
            alert-danger alert-dismissible' role='alert'>\n\
            <button type='button' class='close' data-dismiss='alert' \n\
            aria-label='Close'><span aria-hidden='true'>&times;</span>\n\
            </button>" + button_save.dataset.message + "</div>");
        } else if (keyword_uniqueness && keyword_space_test.length < 2 && keyword_test) {
            
            form.submit();
            //After submitting the form we need to colse the fancy box and reload the page
            if (typeof window.parent.$.fancybox!=='undefined'){
                window.parent.$.fancybox.close();
            }
            parent.location.reload(true);
        }
        //After all checks we need to set keyword_uniqueness back to true,
        //because there might be more attempts to submit the form
        //The same for keyword symbol test
        keyword_uniqueness = true;
        keyword_test = true;
        
        
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