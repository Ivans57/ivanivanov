@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    @if ($all_folders_count > 1)
        <div class="albums-or-articles-search">
            {!! Form::label('search_folders', Lang::get('keywords.SearchForFolders').':', 
                           ['class' => 'albums-or-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'folders', 
                           (($what_to_search === 'folders') ? true : false), ['id' => 'search_folders', 
                            'class' => 'albums-or-articles-what-to-search']); !!}                    
            {!! Form::label('search_articles', Lang::get('keywords.SearchForArticles').':', 
                           ['class' => 'albums-or-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'articles', 
                           (($what_to_search === 'articles') ? true : false), ['id' => 'search_articles', 
                            'class' => 'albums-or-articles-what-to-search']); !!}
            {!! Form::text('search', null, 
                ['class' => 'albums-or-articles-search-input', 
                 'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'albums-or-articles-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                     'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="albums-or-articles-content">
        @include('pages.folders_and_articles.folders_content')
    </div>
</article>
@stop