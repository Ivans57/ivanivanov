@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-albums">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/albums" : "/ru/admin/albums" }} class="path-panel-text">@lang('keywords.Albums')</a>
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
    <div class="admin-panel-albums-or-articles-title">
        @include('adminpages.albums.adminalbum_album_title')
    </div>
    <div class="admin-panel-albums-control-buttons" id="control_buttons">
        @include('adminpages.albums.adminalbum_controlbuttons')
    </div>
    <!-- Consider conditions of displaying the elements below.-->
    <div class="admin-panel-albums-search">
        {!! Form::label('search_albums', Lang::get('keywords.SearchForAlbums').':', ['class' => 'admin-panel-albums-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'albums', 
                       (($what_to_search === 'albums') ? true : false), ['id' => 'search_albums', 'class' => 'admin-panel-albums-what-to-search']); !!}                    
        {!! Form::label('search_pictures', Lang::get('keywords.SearchForPictures').':', ['class' => 'admin-panel-albums-what-to-search-label']) !!}               
        {!! Form::radio('what_to_search', 'pictures', 
                       (($what_to_search === 'pictures') ? true : false), ['id' => 'search_pictures', 'class' => 'admin-panel-albums-what-to-search']); !!}
        {!! Form::text('search', null, ['class' => 'admin-panel-albums-search-input', 
                           'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search', 'id' => 'search']) !!}
        {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                        ['class' => 'admin-panel-albums-search-button', 'id' => 'search_button', 'title' => Lang::get('keywords.Search'), 
                         'data-section' => $section, 'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
    </div>
     <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-albums-or-articles-content">
        @include('adminpages.albums.adminalbum_content')
    </div>   
</article>

@stop
