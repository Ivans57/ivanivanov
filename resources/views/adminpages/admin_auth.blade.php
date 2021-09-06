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
    <script type="text/javascript" src="{{ URL::asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ URL::asset('bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>
</html>