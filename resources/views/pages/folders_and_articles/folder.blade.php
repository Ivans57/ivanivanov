@extends('app')

@section('content')

<article class="{{ $articleAmount < 1 ? "website-main-article articles-article-folders" : "website-main-article articles-article-folders-and-articles" }}">       
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/articles" : "/ru/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents, 'is_admin_panel' => false])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent                
        @endif
    </div>
    <!-- The class below is only for js purposes -->
    <div class="albums-or-articles-title">
        @include('pages.folders_and_articles.folder_folder_title')
    </div>
    <div class="albums-or-articles-search">
        {!! Form::select('what_to_search', array(
                             'folders' => Lang::get('keywords.SearchForFolders'),
                             'articles' => Lang::get('keywords.SearchForArticles')),
                              $what_to_search, ['id' => 'what_to_search', 
                              'class' => 'form-control folders-and-articles-sorting-controls folders-and-articles-sorting-select']) !!}
        
        <!-- {!! Form::label('search_folders', Lang::get('keywords.SearchForFolders').':', 
                       ['class' => 'albums-or-articles-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'folders', 
                       (($what_to_search === 'folders') ? true : false), ['id' => 'search_folders', 
                        'class' => 'albums-or-articles-what-to-search']); !!}                    
        {!! Form::label('search_articles', Lang::get('keywords.SearchForArticles').':', 
                       ['class' => 'albums-or-articles-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'articles', 
                       (($what_to_search === 'articles') ? true : false), ['id' => 'search_articles', 
                        'class' => 'albums-or-articles-what-to-search']); !!} -->               
        {!! Form::text('search', null, 
                      ['class' => 'albums-or-articles-search-input', 
                       'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
        {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                        ['class' => 'albums-or-articles-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                         'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="albums-or-articles-content">
        @include('pages.folders_and_articles.folder_content')
    </div>   
</article>
@stop