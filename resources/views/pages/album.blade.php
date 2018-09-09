@extends('app')

@section('content')
<article class="website-main-article albums-article">
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
    @if ($albums_and_pictures_total_number > 0)
        <section class="external-albums-picture-wrapper">
            <div class="albums-picture-wrapper">       
                @foreach ($albums_and_pictures as $album_or_picture)
                    @if ($album_or_picture->type == 'album')
                        <div class="album-item">
                            <div class="album-body">
                                @if (App::isLocale('en'))
                                    <a href='/albums/{{ $album_or_picture->keyWord }}/page/1'>
                                        <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->caption }}" class="album-folder">
                                    </a>
                                @else
                                    <a href='/ru/albums/{{ $album_or_picture->keyWord }}/page/1'>
                                        <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->caption }}" class="album-folder">
                                    </a>
                                @endif
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
        </section>
    @else
        <section class="empty-albums-text-wrapper">
            <p>@lang('albumContent.EmptyAlbum')</p>
        </section>
    @endif
    @if ($albums_and_pictures_total_number > 20)
        <section class="paginator">
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
        </section>
    @endif
</article>
@stop