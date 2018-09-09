<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{!! $headTitle !!}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            
            .lang-links {
                text-align: right;
                margin-top: 15px;
                margin-left: 600px;
                width: 740px;
            }
            
            .lang-link {
                display: inline;
            }
            
            .main-title {
                color: #004d99;
                font-style: italic;
                margin-left: 600px;
            }
            
            .main-links {
                text-align: right;
                margin-top: 35px;
                margin-left: 600px;
                width: 740px;
            }
            
            .main-link {
                display: inline;
            }
            
            .main-body {
                margin-top: 20px;
                margin-left: 600px;
                width: 740px;
            }
            
            /*.main-body p span {
                margin-left: 10px;
            }*/
        </style>
        
        <!-- These lines we need temporary for bootstrap. When find out how to use it through Laravel can delete them. -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- -->
        
        <!-- All scripts -->
        <!-- Nothing at the moment -->
       
    </head>
    <body>
        
        <div class='lang-links'>
            <!-- In the next line we can see how we can perform a check for a current localization -->
            <!-- Depends on the current localization we use a proper variable -->
            @if (App::isLocale('en'))
            <a href='{{ action('HomeController@index') }}' class='lang-link'>
                {!! $en_language !!}
            </a>
            <a href='/ru' class='lang-link'>
                {!! $ru_language !!}
            </a>
            @else
            <a href='{{ action('HomeController@index') }}' class='lang-link'>
                {!! $en_language !!}
            </a>
            <a href='/ru' class='lang-link'>
                {!! $ru_language !!}
            </a>
            @endif   
        </div>
        <div>
            <h1 class="main-title">
                <!-- In the next line we can see how we fetch necessary text from our localization file and how we can call it -->
                @lang('mainTitle.PersonalWebPageOfIvanIvanov')
            </h1>    
        </div>
        <div class='main-links'>
            <!-- Here we are embedding main links of our web-site -->
            @yield('main_links')
        </div>
        <div class="main-body">
            <!-- Here we are embedding necessary content which is dependent on the selected link -->
            @yield('content')
        </div>
    </body> 
</html>
