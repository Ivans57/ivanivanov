<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | Login
            @endslot
            @slot('css')
                <!--The line below needs to be changed, different styles need to be applied. -->
                <link href="{{ URL::asset('css/pop_up_window_layout.css') }}" rel="stylesheet">
            @endslot
        @endcomponent     
    </head>
    <body>
        <div>
            @yield('admin_login')
        </div>
    </body>
</html>