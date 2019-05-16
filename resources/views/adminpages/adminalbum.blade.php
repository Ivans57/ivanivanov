@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    @if ($albumParents == 0)
        <div class="path-panel">
            <span class="path-panel-text">@lang('keywords.Path'):</span>
            @if (App::isLocale('en'))
                <a href='/admin/albums' class="path-panel-text"> @lang('keywords.Albums')</a>
            @else
                <a href='/ru/admin/albums' class="path-panel-text"> @lang('keywords.Albums')</a>
            @endif
            <span class="path-panel-text"> /</span>
        </div>
    @else
        <div class="path-panel">
            <span class="path-panel-text">@lang('keywords.Path'):</span>
            @if (App::isLocale('en'))
                <a href='/admin/albums' class="path-panel-text"> @lang('keywords.Albums')</a>
            @else
                <a href='/ru/admin/albums' class="path-panel-text"> @lang('keywords.Albums')</a>
            @endif
            <span class="path-panel-text"> /</span>
            @foreach ($albumParents as $albumParent)
                @if (App::isLocale('en'))
                    <a href='/admin/articles/{{ $albumParent->keyWord }}/page/1' class="path-panel-text">{{ $albumParent->albumName }}</a>
                @else
                    <a href='/ru/admin/articles/{{ $albumParent->keyWord }}/page/1' class="path-panel-text">{{ $albumParent->albumName }}</a>
                @endif
                <span class="path-panel-text"> /</span>
            @endforeach
        </div>
    @endif
    @if ($albums_and_pictures_total_number > 0)
        <div>
            <h2>{{ $headTitle }}</h2>
        </div>
        <div class="admin-panel-albums-add-picture-album-wrapper">
            <div class="admin-panel-albums-add-picture-album-button">
                <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddPicture')</a>
            </div>
            <div class="admin-panel-albums-add-picture-album-button">
                <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddAlbum')</a>
            </div>
        </div>
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">          
                @foreach ($albums_and_pictures as $album_or_picture)
                    @if ($album_or_picture->type == 'album')
                        <div class="admin-panel-albums-picture-and-album-item">
                            <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                                </div>
                                <div class="admin-panel-albums-picture-and-album-title">
                                    <p>{{ $album_or_picture->caption }}</p>
                                </div>
                            </div>
                            <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                                <div class="admin-panel-albums-picture-and-album-control-buttons">
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        @if (App::isLocale('en'))
                                            <a href='/admin/albums/{{ $album_or_picture->keyWord }}/page/1' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                        @else
                                            <a href='/ru/admin/albums/{{ $album_or_picture>keyWord }}/page/1' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                        @endif
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($album_or_picture->type == 'picture')
                        <div class="admin-panel-albums-picture-and-album-item">
                            <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ URL::asset('images/icons/article.png') }}">
                                </div>
                                <div class="admin-panel-albums-picture-and-album-title">
                                    <p>{{ $album_or_picture->caption }}</p>
                                </div>
                            </div>
                            <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                                <div class="admin-panel-albums-picture-and-album-control-buttons">
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach     
            </div>
        </div>
        @if ($albums_and_pictures_total_number > $items_amount_per_page)
            <div class="admin-panel-paginator">
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
    @else
        <div>
            <h2>{{ $headTitle }}</h2>
        </div>    
        <div class="admin-panel-albums-add-picture-album-wrapper">
            <div class="admin-panel-albums-add-picture-album-button">
                <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddPicture')</a>
            </div>
            <div class="admin-panel-albums-add-picture-album-button">
                <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddAlbum')</a>
            </div>
        </div>
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
