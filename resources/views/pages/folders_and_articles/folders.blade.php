@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    @if ($all_folders_count > 1)
        <div class="albums-or-articles-search">
            {!! Form::select('what_to_search', array(
                             'folders' => Lang::get('keywords.SearchForFolders'),
                             'articles' => Lang::get('keywords.SearchForArticles')),
                              $what_to_search, ['id' => 'what_to_search', 
                              'class' => 'albums-or-articles-what-to-search']) !!}           
            <!-- The <div> below is required to keep input and button together when changing screen size. -->
            <div>
                {!! Form::text('search', null, 
                    ['class' => 'albums-or-articles-search-input', 
                     'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
                {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                        ['class' => 'albums-or-articles-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                         'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
            </div>
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="albums-or-articles-content">
        @include('pages.folders_and_articles.folders_content')
    </div>
</article>
@stop