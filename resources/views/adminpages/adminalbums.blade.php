@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-articles-add-article-folder-wrapper">
        <div class="admin-panel-articles-add-article-folder-button">
            <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddAlbum')</a>
        </div>
    </div>         
    @if ($albums->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">
                @foreach ($albums as $album)
                    <div class="admin-panel-articles-article-and-folder-item">
                        <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                            <div class="admin-panel-articles-article-and-folder-picture">
                                <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                            </div>
                            <div class="admin-panel-articles-article-and-folder-title">
                                <p>{{ $album->album_name }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                            <div class="admin-panel-articles-article-and-folder-control-buttons">
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='articles/{{ $album->keyword }}/page/1' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Open')</a>
                                </div>
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Edit')</a>
                                </div>
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Delete')</a>
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
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop
