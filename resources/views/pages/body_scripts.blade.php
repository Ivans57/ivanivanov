<!-- !!!According to html standards all scripts should be placed below before </body> tag!!! -->
<!-- We need a line below to use a jQuery in this project -->
<script type="text/javascript" src="{{ URL::asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>


<!-- We need this to use our scripts -->
{{ $js }}
    
<!-- We need the code below to implement fancyBox in our project -->
<script type="text/javascript" src="{{ URL::asset('fancybox-master/dist/jquery.fancybox.js') }}"></script>

<!-- We need the line below to make it possible for javascript to decode some 
special symbols (e.g. copyright) -->
<script type="text/javascript" src="{{ URL::asset('he-master/he.js') }}"></script>