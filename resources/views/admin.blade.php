<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | {!! $headTitle !!}
            @endslot
            @slot('css')
                <link href="{{ URL::asset('css/admin_layout.css') }}" rel="stylesheet">
                <link href="{{ URL::asset('css/pop_up_window_layout.css') }}" rel="stylesheet">
            @endslot
        @endcomponent 
    </head>
    <body>
        <div class="admin-panel-wrapper">
            <header class="admin-panel-header">
                @component('adminpages/admin_body_header', ['main_links' => $main_links])
                    @slot('keywordsLinkIsActive')
                        {!! $keywordsLinkIsActive !!}
                    @endslot
                @endcomponent
            </header>
            <main>
                @yield('admincontent')
            </main>
            <footer class="admin-panel-footer">
                @component('adminpages/admin_body_footer')
                @endcomponent
            </footer>
        </div>
        <!-- Scripts -->
        @component('pages/body_scripts')
            @slot('js')
                <script type="text/javascript" src="{{ URL::asset('js/admin_panel_javascript.js') }}"></script>
                <!-- This check is required because the file which is mentioned below will be used only when making a new article. -->
                @if (isset($section, $mode))
                    <script type="text/javascript" src="{{ URL::asset('js/parent_search_and_select.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('js/article_create_edit_cancel.js') }}"></script>
                @endif
            @endslot
        @endcomponent
        <!-- End of scripts -->
    </body>
</html>
