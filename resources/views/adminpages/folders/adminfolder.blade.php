@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-articles">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/articles" : "/ru/admin/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)    
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents, 'is_admin_panel' => true])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent
        @endif
    </div>
    <div class="admin-panel-articles-title">
        @include('adminpages.folders.adminfolder_folder_title')
    </div>
    <div class="admin-panel-articles-add-article-folder-wrapper">
        <div class="admin-panel-articles-control-buttons">
            @include('adminpages.folders.adminfolder_controlbuttons')
        </div>
    </div>
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
                         'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-articles-content">
        @include('adminpages.folders.adminfolder_content')
    </div>   
</article>

@stop
