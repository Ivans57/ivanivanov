
/*Scripts for Users Control Buttons (when need to add users to albums and folders).*/

$( document ).ready(function() {
    //Need the few lines below, otherwise POST requests for ajax won't work.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //Here we need to change a view of the button when user clicks on it.
    $(document).on("click", ".admin-panel-user-control-button", function() {
        $(this).removeClass("admin-panel-user-control-button").addClass("admin-panel-user-control-button-pressed");
    });
    
    function users_control_button_view_change_after_fancybox_close() {
        //We don't need an array here as in previous examples, because there will be
        //always only one pressed element.
        var control_button = document.querySelector('.admin-panel-user-control-button-pressed');
        unclickButton(control_button);

        function unclickButton(control_button) {
            control_button.classList.remove('admin-panel-user-control-button-pressed');
            control_button.classList.add('admin-panel-user-control-button');
        }
    }
    
    $(document).on("click", "#add_user_button", function() {
        var localization = ($('#useful_data').data('localization') === "en") ? "" : "/ru";
        var url = localization+'/admin/users-add-edit-delete/create-for-albums';
        add_user(url, '545px');      
    });
    
    function add_user(url, height) {
        //We need this script to open existing Album edit page in fancy box window.
        //Also we need this script to open existing Picture edit page in fancy box window.
        $.fancybox.open({
            type: 'iframe',
            src: url,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : false,
                css : {
                    'width' : '355px',
                    'height' : height,
                    'margin-bottom' : '200px'
                }
            },
            //Also we will need a function which will recover add button's view after
            //closing pop up's window without adding a new keyword.
            afterClose: function() {
                users_control_button_view_change_after_fancybox_close();
            }
        });
    }
});

/*--------------------------------------------------------*/