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
        <!-- Scripts -->
        @component('pages/body_scripts')
            @slot('js')
                <script type="text/javascript" src="{{ URL::asset('js/partial_javascript.js') }}"></script>
            @endslot
        @endcomponent
        <!-- End of scripts -->
    </body>
</html>
