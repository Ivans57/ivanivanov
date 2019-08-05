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
    </head>
    <body>
        <div>
            @yield('partialcontent')
        </div>
        @yield('scripts')
    </body>
</html>
