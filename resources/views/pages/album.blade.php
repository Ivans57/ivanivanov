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
                    "/ru/albums/".$folderParent->keyWord."/page/1" }} class="path-panel-text">{{ $albumParent->albumName }}</a>
                <span class="path-panel-text"> /</span>
            @endforeach        
        @endif
    </div>
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
    @if ($albums_and_pictures_total_number > 0)
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
    @else
        <div class="empty-albums-text-wrapper">
            <p>@lang('albumContent.EmptyAlbum')</p>
        </div>
    @endif
    @if ($albums_and_pictures_total_number > 20)
        <div class="paginator">
            @if ($albums_and_pictures_current_page == 1)
               <span class="first-inactive"></span>
            @else
               <a href="1" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
            @endif
            @if ($albums_and_pictures_current_page == 1)
               <span class="previous-inactive"></span>
            @else
               <a href="{{ $albums_and_pictures_previous_page }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
            @endif
            <span class="pagination-info">{{ $albums_and_pictures_current_page }} @lang('pagination.Of') {{ $albums_and_pictures_number_of_pages }}</span>
            @if ($albums_and_pictures_current_page == $albums_and_pictures_number_of_pages)
               <span class="next-inactive"></span>
            @else
               <a href="{{ $albums_and_pictures_next_page }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
            @endif
            @if ($albums_and_pictures_current_page == $albums_and_pictures_number_of_pages)
               <span class="last-inactive"></span>
            @else
               <a href="{{ $albums_and_pictures_number_of_pages }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
            @endif
        </div>
    @endif
</article>
@stop