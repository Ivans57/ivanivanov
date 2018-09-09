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
        <!-- !!!Check Bootstrap version!!! -->
    
        <!-- This is main bootstrap link. Without it my project won't implement any bootstrap view. -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
        <!-- The line below makes my project look like more beautiful. I paid my attention if I use this line buttons look like more 3D. Optional! -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <!-- This was in bootstrap template example. I commented this. May be we can need it in a future in case any issues with IE10 -->
        <!-- <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->

        <!-- Custom styles for this page. -->
    
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('fancybox-master/dist/jquery.fancybox.css') }}" />
    
        <link href="{{ URL::asset('css/main_layout.css') }}" rel="stylesheet">
    
        <!-- This link we need to provide a nice font for main title in a russian version-->
        <link href="http://allfont.ru/allfont.css?fonts=bikham-cyr-script" rel="stylesheet" type="text/css" />
    
        <!-- End of styles -->
    
    </head>

    <body>
        <div class="website-wrapper" id="website-wrapper">
            <header class="website-header">
                <div class="website-language-and-title">
                    <div class="website-language-select">
                        <div>
                            <a href='{{ action('HomeController@index') }}'>
                                @if (App::isLocale('en'))
                                    <img src="{{ URL::asset('images/icons/uk.png') }}" alt="uk" title=@lang('langLinks.English') class="website-en-laguage-select-button website-laguage-select-button-pressed" id="en-laguage-button">
                                @else
                                    <img src="{{ URL::asset('images/icons/uk.png') }}" alt="uk" title=@lang('langLinks.English') class="website-en-laguage-select-button" id="en-laguage-button">
                                @endif
                            </a>
                        </div>
                        <div>
                            <a href='/ru'>
                                @if (App::isLocale('en'))
                                    <img src="{{ URL::asset('images/icons/rus.png') }}" alt="rus" title=@lang('langLinks.Russian') class="website-rus-laguage-select-button" id="rus-laguage-button">
                                @else
                                    <img src="{{ URL::asset('images/icons/rus.png') }}" alt="rus" title=@lang('langLinks.Russian') class="website-rus-laguage-select-button website-laguage-select-button-pressed" id="rus-laguage-button">
                                @endif
                            </a>
                        </div>    
                    </div>   
                    <div class="website-title">
                        <h1>@lang('mainTitle.PersonalWebPageOfIvanIvanov')</h1>
                    </div>
                </div>
                <nav class="website-menu">              
                    <!-- Below we can we perform a check for a localization and depends on it we choose a proper action for selected link -->
                    <!-- In the first case we a going directly to a necessary controller. default (English) language settings will be considered -->
                    <!-- In the second case we use a link so we will go through the whole route and current language setting could take an effect -->
                    <!-- To understand this please pay attention to a web route file! -->
                    <!-- !!!Important!!! In the future peace of code of navigation bar needs to be located only on app page, 
                    because we are repeating the same code on each page. This is not good! -->
                    <!-- I wrapped my <div> by <a> tags to make pointer to change its view while it is hovering over this <div> -->
                    <div class="website-menu-compact-view" id="menu-compact-view"><div></div></div>                    
                    @foreach ($main_links as $main_link)
                        <div class="website-menu-normal-view-button">
                            @if ($main_link->isActive == true)
                                <a href='{{ $main_link->webLinkName }}' class="website-menu-button-link website-menu-button-pressed">{{ $main_link->linkName }}</a>
                            @else
                                <a href='{{ $main_link->webLinkName }}' class="website-menu-button-link">{{ $main_link->linkName }}</a>
                            @endif
                        </div>
                    @endforeach                       
                </nav>
            </header>
            <main>
                <!-- Here we are embedding necessary content which is dependent on the selected link -->
                @yield('content')
            </main>
            <footer class="website-footer">
                <!-- Line below I left from bootstrap example just in case-->  
                <!-- <p>&copy; 2016 Company, Inc.</p> -->
                <p>@lang('mainFooter.NonCommercial')</p>
            </footer>
        </div>
        <!-- I have excluded pop-up-menu from website-wrapper because when we
        use it, the content of website-wrapper which is on the background 
        should be static (with fixed position), but at the same time our 
        mobile view menu if necessary should be scrolled (with absolute 
        position).-->
        <div class="pop-up-menu">
            <div class="pop-up-menu-top">
                <!-- Drawing image of cross to close our pop-up menu. -->
                <svg width="20px" height="20px" class="pop-up-menu-close" id="pop-up-menu-close">
                    <!-- <rect width="20" height="20" fill="none" stroke="#333333" stroke-width="2" /> -->
                        <line x1="0" y1="0" x2="20" y2="20" stroke="#333333" stroke-width="2" />
                        <line x1="0" y1="20" x2="20" y2="0" stroke="#333333" stroke-width="2" />
                </svg>
            </div>
            <div class="pop-up-menu-body">
                <h1>@lang('mainTitle.PersonalWebPageOfIvanIvanov')</h1>
                <ul>
                    @foreach ($main_links as $main_link)
                        <li>
                            @if ($main_link->isActive == true)
                                    <a href='{{ $main_link->webLinkName }}' class="pop-up-menu-link pop-up-menu-link-active">{{ $main_link->linkName }}</a>
                                @else
                                    <a href='{{ $main_link->webLinkName }}' class="pop-up-menu-link">{{ $main_link->linkName }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Scripts -->
        
        <!-- !!!According to html standards all scripts should be placed below before </body> tag!!! -->
        <!-- We need a line below to use a jQuery in this project -->
        <script type="text/javascript" src="{{ URL::asset('js/jquery-3.3.1.min.js') }}"></script>
    
        <!-- We need this to use our scripts -->    
        <script type="text/javascript" src="{{ URL::asset('js/main_javascript.js') }}"></script>
    
        <!-- We need a code below to implement fancyBox in our project -->
        <script type="text/javascript" src="{{ URL::asset('fancybox-master/dist/jquery.fancybox.js') }}"></script>

        <!-- End of scripts -->    
    </body>
</html>
