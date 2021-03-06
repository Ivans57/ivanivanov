<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                {!! $headTitle !!}
            @endslot
            @slot('css')
                <link href="{{ URL::asset('css/main_layout.css') }}" rel="stylesheet">
            @endslot
        @endcomponent
    </head>
    <body>
        <div class="website-wrapper" id="website-wrapper">
            <header class="website-header">
                @if ($main_links !== null)
                    @component('pages/body_header', ['main_links' => $main_links])
                    @endcomponent
                @endif
            </header>
            <main>
                <!-- Here we are embedding necessary content which is dependent on the selected link -->
                @yield('content')
            </main>
            <footer class="website-footer">
                @component('pages/body_footer')
                @endcomponent
            </footer>
        </div>
        @if ($main_links !== null)
            @component('pages/body_pop_up_menu', ['main_links' => $main_links])
            @endcomponent
        @endif
        <!-- Scripts -->
        @component('pages/body_scripts')
            @slot('js')
                <script type="text/javascript" src="{{ URL::asset('js/main_javascript.js') }}"></script>
                <script type="text/javascript" src="{{ URL::asset('js/article_javascript.js') }}"></script>
            @endslot
        @endcomponent
        <!-- End of scripts -->    
    </body>
</html>
