<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- We need a line below to make a nice icon on our bookmark. I have temporary commented this.
    We will activate this when we make our logo. --> 
    <!-- <link rel="icon" href="../../favicon.ico"> -->

    <title>{!! $headTitle !!}</title>

    <!-- Styles -->
    
    <!-- Bootstrap core CSS -->
    
    <!-- This is main bootstrap link. Without it my project won't implement any bootstrap view. -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- The line below makes my project look like more beautiful. I paid my attention if I use this line buttons look like more 3D. Optional! -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- This was in bootstrap template example. I commented this. May be we can need it in a future in case any issues with IE10 -->
    <!-- <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->

    <!-- Custom styles for this page. -->
    
    <link rel="stylesheet" href="{{ URL::asset('css/main-layout.css') }}">    
    
    <!-- End of styles -->

  </head>

  <body>

    <div class="container">
      <div class="header clearfix">      
        <nav>
          @yield('main_links')
        </nav>
          <h3 class="text-muted">
              <!-- In the next line we can see how we fetch necessary text from our localization file and how we can call it -->
                @lang('mainTitle.PersonalWebPageOfIvanIvanov')
          </h3>
      </div>

        
      <!-- +++++++++++++++++++++++++++++++++++++++++ -->
        
      <div>
            <!-- Here we are embedding necessary content which is dependent on the selected link -->
            @yield('content')
     </div>

      

      
      <!-- +++++++++++++++++++++++++++++++++++++++++ -->
      
      <footer class="footer">
        <!-- Line below I left from bootstrap example just in case-->  
        <!-- <p>&copy; 2016 Company, Inc.</p> -->
        <p>@lang('mainFooter.NonCommercial')</p>
      </footer>

    </div> <!-- /container -->
    
    <!-- Scripts -->
    
    <!-- !!!According to html standards all scripts should be placed below before </body> tag!!! -->
    <!-- We need a line below bootstrap scripts to work properly -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      
    <!-- This was in bootstrap template example. I commented this. May be we can need it in a future in case any issues with IE10 -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
    
    <!-- End of scripts -->
    
  </body>
</html>
