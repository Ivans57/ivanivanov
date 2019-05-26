@extends('app')

@section('content')
<article class="website-main-article albums-article">
    <div class="path-panel">        
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/albums" : "/ru/albums" }} class="path-panel-text">@lang('keywords.Albums')</a>
        <span class="path-panel-text"> /</span>                   
        @if ($albumParents > 0)           
            @foreach ($albumParents as $albumParent)
                <a href={{ App::isLocale('en') ? "/albums/".$albumParent->keyWord."/page/1" : 
                    "/ru/albums/".$albumParent->keyWord."/page/1" }} class="path-panel-text">{{ $albumParent->albumName }}</a>
                <span class="path-panel-text"> /</span>
            @endforeach        
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
                                        <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->caption }}" class="album-folder">
                                </a>
                            </div>
                            <div class="album-title">
                                <h3 class="album-folder-title">{{ $album_or_picture->caption }}</h3>
                            </div>
                        </div>
                    @else
                        <div class="album-picture">
                            <a class="album_picture_link" href="{{ URL::asset('images/pages/albums/'.$albumName.'/'.$album_or_picture->keyWord.$album_or_picture->fileExtension) }}" data-fancybox="group" data-caption="{{ $album_or_picture->caption }}" title="{{ $album_or_picture->caption }}">
                                <img src="{{ URL::asset('images/pages/albums/'.$albumName.'/small/'.$album_or_picture->keyWord.$album_or_picture->fileExtension) }}" alt="{{ $album_or_picture->caption }}" class="album-picture-big">
                            </a>
                        </div>
                    @endif
                @endforeach       
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            @component('paginator')
                @slot('pagination_info')
                    {!! $pagination_info !!}
                @endslot
            @endcomponent
        @endif
    @else
        <div class="empty-albums-text-wrapper">
            <p>@lang('albumContent.EmptyAlbum')</p>
        </div>
    @endif
</article>
@stop