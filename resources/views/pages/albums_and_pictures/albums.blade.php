@extends('app')

@section('content')
<article class="website-main-article albums-article">
    @if ($all_albums_count > 1)
        <div class="albums-or-articles-search">
            <!-- {!! Form::label('search_albums', Lang::get('keywords.SearchForAlbums').':', 
                           ['class' => 'albums-or-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'albums', 
                           (($what_to_search === 'albums') ? true : false), ['id' => 'search_albums', 
                            'class' => 'albums-or-articles-what-to-search']); !!}                    
            {!! Form::label('search_pictures', Lang::get('keywords.SearchForPictures').':', 
                           ['class' => 'albums-or-articles-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'pictures', 
                           (($what_to_search === 'pictures') ? true : false), ['id' => 'search_pictures', 
                            'class' => 'albums-or-articles-what-to-search']); !!} -->
            {!! Form::select('what_to_search', array(
                             'albums' => Lang::get('keywords.SearchForAlbums'),
                             'pictures' => Lang::get('keywords.SearchForPictures')),
                              $what_to_search, ['id' => 'what_to_search', 
                              'class' => 'albums-or-articles-what-to-search']) !!}
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
        @include('pages.albums_and_pictures.albums_content')
    </div>
</article>
@stop