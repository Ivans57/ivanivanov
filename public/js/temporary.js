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
    
    var image_select = document.getElementById('image_select');
    var file_caption = document.getElementById('pseudo_image_select_file_name');
    
    image_select.addEventListener('change', function() {
        //There might be a file with very long name, in that case on a view we will shorten its name.
        //We will always take element 0 as we will always add only one picture.
        var new_file_name = this.files[0].name;
        var new_file_names_length = new_file_name.length;
        if ((new_file_names_length) > 22) {
            var new_file_name_begining = new_file_name.slice(0, 13);
            var new_file_name_end = new_file_name.slice(-7);
            new_file_name = new_file_name_begining.concat("...");
            new_file_name = new_file_name.concat(new_file_name_end);
        }      
        file_caption.innerHTML = new_file_name;
    });
});
