@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-articles">
    <div class="admin-panel-articles-control-buttons">
        @include('adminpages.folders.adminfolders_controlbuttons')
    </div>
    @if ($all_folders_count > 1)
        <div>
            {!! Form::label('search_folders', 'Search for Folders', 
                           ['class' => 'admin-panel-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'folders', 
                           (($what_to_search === 'folders') ? true : false), ['id' => 'search_folders', 
                            'class' => 'admin-panel-articles-what-to-search']); !!}                    
            {!! Form::label('search_articles', 'Search for Articles', 
                           ['class' => 'admin-panel-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'articles', 
                           (($what_to_search === 'articles') ? true : false), ['id' => 'search_articles', 
                            'class' => 'admin-panel-articles-what-to-search']); !!}
        </div>
        <div class="admin-panel-keywords-search">
            {!! Form::text('folder_search', null, 
                ['class' => 'admin-panel-keywords-search-input', 
                 'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'folder_search', 'id' => 'folder_search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'admin-panel-keywords-search-button', 'id' => 'folder_search_button', 'title' => Lang::get('keywords.Search'), 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-articles-content">
        @include('adminpages.folders.adminfolders_content')
    </div>
</article>

@stop
