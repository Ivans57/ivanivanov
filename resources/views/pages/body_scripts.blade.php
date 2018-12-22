<!-- !!!According to html standards all scripts should be placed below before </body> tag!!! -->
<!-- We need a line below to use a jQuery in this project -->
<script type="text/javascript" src="{{ URL::asset('js/jquery-3.3.1.min.js') }}"></script>
    
<!-- We need this to use our scripts -->
{{ $js }}
    
<!-- We need a code below to implement fancyBox in our project -->
<script type="text/javascript" src="{{ URL::asset('fancybox-master/dist/jquery.fancybox.js') }}"></script>