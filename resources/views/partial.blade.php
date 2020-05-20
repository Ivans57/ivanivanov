<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | {!! $headTitle !!}
            @endslot
            @slot('css')
                <link href="{{ URL::asset('css/admin_layout.css') }}" rel="stylesheet">
            @endslot
        @endcomponent
        <!-- Need to relocate the line below -->
        <script src="https://kit.fontawesome.com/de385ec762.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div>
            @yield('partialcontent')
        </div>
        @yield('scripts')
    </body>
</html>
