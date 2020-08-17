//If scripts or CSS do not take an effect in Mozilla Firefox Chrome , press Ctrl+F5!

$( document ).ready(function() {
    var image_link =document.getElementById('my_img');
    var children = image_link.childNodes;
    var picture_source = children[0].src;
    image_link.href = picture_source;
});