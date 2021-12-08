/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*Scripts for Users Control Buttons (when need to add users to albums and folders).*/

$( document ).ready(function() {
    $(function () {
        $("#users").change(function () {
            var user_permission_status = $(this).find(':selected').data('access');
            if (user_permission_status === "full") {
                $("#full_access").prop("checked", true);
            } else if (user_permission_status === "limited") {
                $("#full_access").prop("checked", false);
            }            
        });
    });
});
