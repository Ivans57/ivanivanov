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
                    <link rel="stylesheet" href="{{ URL::asset('sceditor-2.1.3/minified/themes/default.min.css') }}" id="theme-style" />
                @endif
            @endslot
        @endcomponent 
    </head>
    <body>
        <div class="admin-panel-wrapper">
            <header class="admin-panel-header">
                @component('adminpages/admin_body_header', ['current_user_name' => $current_user_name, 'main_ws_links' => $main_ws_links, 'main_ap_links' => $main_ap_links])
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
                @if (isset($section))
                    @if ($section == 'keywords')
                            <script type="text/javascript" src="{{ URL::asset('js/adminkeywords.js') }}"></script>
                    @elseif ($section == 'users')
                            <script type="text/javascript" src="{{ URL::asset('js/adminusers.js') }}"></script>
                    @elseif ($section == 'albums')
                            <script type="text/javascript" src="{{ URL::asset('js/adminalbums.js') }}"></script>
                            <script type="text/javascript" src="{{ URL::asset('js/admin_albums_and_articles_sort_and_search.js') }}"></script>
                    @elseif ($section == 'articles')
                            <script type="text/javascript" src="{{ URL::asset('js/adminarticles.js') }}"></script>
                            <script type="text/javascript" src="{{ URL::asset('js/admin_albums_and_articles_sort_and_search.js') }}"></script>
                    @endif
                @endif
                <!-- The js in the condition below will be applied only when admin user is active and only for albums and articles sections.-->
                @if (isset($user_role) && isset($section))
                    @if ($user_role == 'admin')
                        <script type="text/javascript" src="{{ URL::asset('js/user_controlbuttons.js') }}"></script>
                    @endif
                @endif
                <!-- This check is required, because the js files which are mentioned below will be used only when making a new article. -->
                @if (isset($mode))
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
