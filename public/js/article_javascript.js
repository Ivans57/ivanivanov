//If scripts or CSS do not take an effect in Mozilla Firefox Chrome , press Ctrl+F5!

$( document ).ready(function() {
    //We have to assign data-caption using javascript, because article body html is getting generated from BBCode 
    //and due to error reasons we cannot do the same using php.
    var picture_link = document.getElementsByClassName("article-body-image-link");
    if (picture_link.length > 0) {       
        for (var i = 0; i < picture_link.length; i++) {
            picture_link[i].dataset.caption = picture_link[i].title;
        }       
    }  
    $(".article-body-image-link").fancybox({
        loop            : false,
        protect         : true,
        transitionEffect	: 'slide',
        transitionDuration : 866,
        wheel : 'auto'
    });   
});
