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

        <title>@lang('keywords.AdministrationPanel') | {!! $headTitle !!}</title>

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
    
        <link href="{{ URL::asset('css/admin_layout.css') }}" rel="stylesheet">
    
        <!-- This link we need to provide a nice font for main title in a russian version-->
        <link href="http://allfont.ru/allfont.css?fonts=bikham-cyr-script" rel="stylesheet" type="text/css" />
    
        <!-- End of styles -->
    
    </head>

    <body>
        <div class="admin-panel-wrapper">
            <header class="admin-panel-header">
                <div class="admin-panel-language-and-title">
                    <div class="admin-panel-language-select">
                        <div>
                            <a href='{{ action('AdminController@index') }}'>
                                @if (App::isLocale('en'))
                                    <p class="admin-panel-en-language-select-button admin-panel-laguage-select-button-pressed">@lang('keywords.EnglishVersion')</p>
                                @else
                                    <p class="admin-panel-en-language-select-button">@lang('keywords.EnglishVersion')</p>
                                @endif
                            </a>
                        </div>
                        <div class="admin-panel-language-select-button-separator">
                            <p>|</p>
                        </div>
                        <div>
                            <a href='/ru/admin'>
                                @if (App::isLocale('en'))
                                    <p class="admin-panel-rus-language-select-button">@lang('keywords.RussianVersion')</p>
                                @else
                                    <p class="admin-panel-rus-language-select-button admin-panel-laguage-select-button-pressed">@lang('keywords.RussianVersion')</p>
                                @endif
                            </a>
                        </div>   
                    </div>   
                    <div class="admin-panel-title">
                        <h1>@lang('keywords.AdministrationPanel')</h1>
                    </div>
                </div>
                <nav class="admin-panel-menu">              
                    @foreach ($main_links as $main_link)
                        @if ($main_link->isActive == true)
                        <a href='{{ $main_link->adminWebLinkName }}' class="admin-panel-menu-link admin-panel-menu-link-pressed">{{ $main_link->linkName }}</a>
                        @else
                            <a href='{{ $main_link->adminWebLinkName }}' class="admin-panel-menu-link">{{ $main_link->linkName }}</a>
                        @endif
                    @endforeach
                    @if ($keywordsLinkIsActive)
                        @if (App::isLocale('en'))
                            <a class="admin-panel-menu-link admin-panel-keywords-menu-link admin-panel-keywords-menu-link-pressed" href='/admin/keywords'>@lang('keywords.Keywords')</a>
                        @else
                            <a class="admin-panel-menu-link admin-panel-keywords-menu-link admin-panel-keywords-menu-link-pressed" href='/ru/admin/keywords'>@lang('keywords.Keywords')</a>
                        @endif
                    @else
                        @if (App::isLocale('en'))
                            <a class="admin-panel-menu-link admin-panel-keywords-menu-link" href='/admin/keywords'>@lang('keywords.Keywords')</a>
                        @else
                            <a class="admin-panel-menu-link admin-panel-keywords-menu-link" href='/ru/admin/keywords'>@lang('keywords.Keywords')</a>
                        @endif
                    @endif
                </nav>
            </header>
            <main>
                @yield('admincontent')
            </main>
            <footer class="admin-panel-footer">
                <div>
                    <p>
                        @lang('keywords.PersonalWebPageOfIvanIvanov')<br>
                        @lang('keywords.NonCommercial')
                    </p>
                </div>
            </footer>
        </div>
        <!-- Scripts -->
        
        <!-- !!!According to html standards all scripts should be placed below before </body> tag!!! -->
        <!-- We need a line below to use a jQuery in this project -->
        <script type="text/javascript" src="{{ URL::asset('js/jquery-3.3.1.min.js') }}"></script>
    
        <!-- We need this to use our scripts -->    
        <script type="text/javascript" src="{{ URL::asset('js/admin_panel_javascript.js') }}"></script>
    
        <!-- We need a code below to implement fancyBox in our project -->
        <script type="text/javascript" src="{{ URL::asset('fancybox-master/dist/jquery.fancybox.js') }}"></script>

        <!-- End of scripts -->
    </body>
</html>
