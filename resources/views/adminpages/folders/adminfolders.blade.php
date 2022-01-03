@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-articles">
    @if ($user_role === 'admin')
        @include('adminpages.users_access_information')
        @include('adminpages.users_controlbuttons')
    @endif
    @if ($all_folders_count > 1)
        <div class="admin-panel-articles-search">
            {!! Form::label('search_folders', Lang::get('keywords.SearchForFolders').':', 
                           ['class' => 'admin-panel-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'folders', 
                           (($what_to_search === 'folders') ? true : false), ['id' => 'search_folders', 
                            'class' => 'admin-panel-articles-what-to-search']); !!}                    
            {!! Form::label('search_articles', Lang::get('keywords.SearchForArticles').':', 
                           ['class' => 'admin-panel-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'articles', 
                           (($what_to_search === 'articles') ? true : false), ['id' => 'search_articles', 
                            'class' => 'admin-panel-articles-what-to-search']); !!}
            {!! Form::text('search', null, 
                ['class' => 'admin-panel-articles-search-input', 
                 'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'admin-panel-articles-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                     'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <div class="admin-panel-articles-control-buttons" id="control_buttons">
        @include('adminpages.folders.adminfolders_controlbuttons')
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-or-articles-content">
        @include('adminpages.folders.adminfolders_content')
    </div>
</article>

@stop
