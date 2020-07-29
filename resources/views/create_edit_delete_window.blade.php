<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | {!! $headTitle !!}
            @endslot
            @slot('css')
                <link href="{{ URL::asset('css/pop_up_window_layout.css') }}" rel="stylesheet">
            @endslot
        @endcomponent     
    </head>
    <body>
        <div>
            @yield('create_edit_delete_window_content')
        </div>
        @yield('scripts')
    </body>
</html>
