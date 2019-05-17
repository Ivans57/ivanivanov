@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-albums-add-picture-album-wrapper">
        <div class="admin-panel-albums-add-picture-album-button">
            <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddAlbum')</a>
        </div>
    </div>         
    @if ($albums->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">
                @foreach ($albums as $album)
                    <div class="admin-panel-albums-picture-and-album-item">
                        <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                            <div>
                                <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                            </div>
                            <div class="admin-panel-albums-picture-and-album-title">
                                <p>{{ $album->album_name }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                            <div class="admin-panel-albums-picture-and-album-control-buttons">
                                <div class="admin-panel-albums-picture-and-album-control-button">
                                    <a href='albums/{{ $album->keyword }}/page/1' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
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
                @endforeach
            </div>
        </div>
        @if ($albums->total() > $items_amount_per_page)
        <div class="admin-panel-paginator">
            @if ($albums->currentPage() == 1)
                <span class="first-inactive"></span>
            @else
                <a href="{{ $albums->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
            @endif
            @if ($albums->currentPage() == 1)
                <span class="previous-inactive"></span>
            @else
                <a href="{{ $albums->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
            @endif
                <span class="pagination-info">{{ $albums->currentPage() }} @lang('pagination.Of') {{ $albums->lastPage() }}</span>
            @if ($albums->currentPage() == $albums->lastPage())
                <span class="next-inactive"></span>
            @else
                <a href="{{ $albums->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
            @endif
            @if ($albums->currentPage() == $albums->lastPage())
                <span class="last-inactive"></span>
            @else
                <a href="{{ $albums->url($albums->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
            @endif
        </div>
        @endif
    @else
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop