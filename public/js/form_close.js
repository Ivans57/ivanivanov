/*The only purpose of the following javascript is to close its form and reload 
 its parent page*/

$( document ).ready(function() {
    if (typeof window.parent.$.fancybox!=='undefined'){
        window.parent.$.fancybox.close();
    }
    parent.location.reload(true);
});