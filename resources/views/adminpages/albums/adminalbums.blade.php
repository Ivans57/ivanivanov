@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-albums">
    <div class="admin-panel-albums-control-buttons">
        @include('adminpages.albums.adminalbums_controlbuttons')
    </div>
    @if ($all_albums_count > 1)
        <div class="admin-panel-albums-search">
            {!! Form::label('search_albums', Lang::get('keywords.SearchForAlbums').':', 
                           ['class' => 'admin-panel-albums-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'albums', 
                           (($what_to_search === 'albums') ? true : false), ['id' => 'search_albums', 
                            'class' => 'admin-panel-albums-what-to-search']); !!}                    
            {!! Form::label('search_pictures', Lang::get('keywords.SearchForPictures').':', 
                           ['class' => 'admin-panel-albums-what-to-search-label']) !!}               
            {!! Form::radio('what_to_search', 'pictures', 
                           (($what_to_search === 'pictures') ? true : false), ['id' => 'search_pictures', 
                            'class' => 'admin-panel-albums-what-to-search']); !!}
            {!! Form::text('search', null, ['class' => 'admin-panel-albums-search-input', 
                           'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                            ['class' => 'admin-panel-albums-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                             'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-content">
        @include('adminpages.albums.adminalbums_content')
    </div>
</article>

@stop
