/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
    //We need the following lines to make ajax requests work.
    //There are special tokens used for security. We need to add them in all heads
    //and also ajax should be set up to pass them.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
     
    document.getElementById("pseudo_image_select").addEventListener("click", function(event){
        document.getElementById('image_select').click();
    });
    
    var input = document.getElementById('image_select');
    var file_caption = document.getElementById('pseudo_image_select_file_name');
    
    input.addEventListener('change', function( e ) {
        //We will always take element 0 as we will always add only one picture.
        //Need to shorten long file names with java script
        file_caption.innerHTML = this.files[0].name;
    });
});
