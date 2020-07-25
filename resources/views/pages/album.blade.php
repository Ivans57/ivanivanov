@extends('app')

@section('content')
<article class="website-main-article albums-article">
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
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
    @if ($total_number_of_items > 0)
        <div class="external-albums-picture-wrapper">
            <div class="albums-picture-wrapper">       
                @foreach ($albums_and_pictures as $album_or_picture)
                    @if ($album_or_picture->type == 'album')
                        <div class="album-item">
                            <div class="album-body">                               
                                <a href={{ App::isLocale('en') ? "/albums/".$album_or_picture->keyWord."/page/1" : 
                                    "/ru/albums/".$album_or_picture->keyWord."/page/1" }}>
                                        <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->caption }}" 
                                            class="album-folder">
                                </a>
                            </div>
                            <div class="album-title">
                                <h3 class="album-folder-title">{{ $album_or_picture->caption }}</h3>
                            </div>
                        </div>
                    @else
                        <div class="album-picture">
                            <a class="album-picture-link" 
                               href="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" 
                               data-fancybox="group" data-caption="{{ $album_or_picture->caption }}" title="{{ $album_or_picture->caption }}">
                                <img src="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" 
                                            alt="{{ $album_or_picture->caption }}" class="album-picture-link-picture">
                            </a>
                        </div>
                    @endif
                @endforeach       
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info])
            @endcomponent
        @endif
    @else
        <div class="empty-albums-text-wrapper">
            <p>@lang('albumContent.EmptyAlbum')</p>
        </div>
    @endif
</article>
@stop