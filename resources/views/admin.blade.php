<!DOCTYPE html>
<html lang="en">
    <head>
        @component('head')
            @slot('head_title')
                @lang('keywords.AdministrationPanel') | {!! $headTitle !!}
            @endslot
            @slot('css')
                <link href="{{ URL::asset('css/admin_layout.css') }}" rel="stylesheet">
                <!-- This check is required, because the css files which are mentioned below will be used only when making a new article. -->
                @if (isset($section, $mode))
                    <link href="{{ URL::asset('css/pop_up_window_layout.css') }}" rel="stylesheet">
                    <link rel="stylesheet" href="{{ URL::asset('sceditor-2.1.3/minified/themes/default.min.css') }}" id="theme-style" />
                @endif
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
                <!-- This check is required, because the js files which are mentioned below will be used only when making a new article. -->
                @if (isset($section, $mode))
                    <script type="text/javascript" src="{{ URL::asset('js/parent_search_and_select.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('js/article_create_edit_cancel.js') }}"></script>
                    <!-- The four scripts below are required to  run WYSIWYG editor -->
                    <script type="text/javascript" src="{{ URL::asset('sceditor-2.1.3/minified/sceditor.min.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('sceditor-2.1.3/minified/icons/monocons.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('sceditor-2.1.3/minified/formats/bbcode.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('sceditor-2.1.3/languages/en.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('sceditor-2.1.3/languages/ru.js') }}"></script>
                    <script type="text/javascript" src="{{ URL::asset('js/wysiwyg_panel.js') }}"></script>
                @endif
            @endslot
        @endcomponent
        <!-- End of scripts -->
    </body>
</html>
