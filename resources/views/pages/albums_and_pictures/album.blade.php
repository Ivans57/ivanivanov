@extends('app')

@section('content')
<article class="website-main-article albums-article albums-article-for-included-albums">
    <div class="path-panel">        
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/albums" : "/ru/albums" }} class="path-panel-text">@lang('keywords.Albums')</a>
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
        @include('pages.albums_and_pictures.album_album_title')
    </div>
    <div class="albums-or-articles-search">
        {!! Form::label('search_albums', Lang::get('keywords.SearchForAlbums').':', 
                       ['class' => 'albums-or-articles-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'albums', 
                       (($what_to_search === 'albums') ? true : false), ['id' => 'search_albums', 
                        'class' => 'albums-or-articles-what-to-search']); !!}                    
        {!! Form::label('search_pictures', Lang::get('keywords.SearchForPictures').':', 
                       ['class' => 'albums-or-articles-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'pictures', 
                       (($what_to_search === 'pictures') ? true : false), ['id' => 'search_pictures', 
                        'class' => 'albums-or-articles-what-to-search']); !!}
        {!! Form::text('search', null, ['class' => 'albums-or-articles-search-input', 
                       'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
        {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                        ['class' => 'albums-or-articles-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                         'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
    </div>
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="albums-or-articles-content">
        @include('pages.albums_and_pictures.album_content')
    </div>
</article>
@stop