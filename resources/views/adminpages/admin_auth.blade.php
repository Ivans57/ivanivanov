<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | Login
            @endslot
            @slot('css')
                <!--The line below needs to be changed, different styles need to be applied. -->
                <link href="{{ URL::asset('css/admin_auth.css') }}" rel="stylesheet">
            @endslot
        @endcomponent     
    </head>
    <body>
        <div class="admin-panel-auth-wrapper">
            <main>
                @yield('admin_login')
            </main>
        </div>
    </body>
</html>