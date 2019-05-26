@extends('app')

@section('content')
<article class="website-main-article albums-article">
    @if ($album_links->count() > 0)
        <div class="external-albums-wrapper">
            <div class="albums-wrapper">
                @foreach ($album_links as $album_link)
                    <div class="album-item">
                        <div class="album-body">
                            <a href='albums/{{ $album_link->keyword }}/page/1'>
                                <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_link->album_name }}" class="album-folder">
                            </a>
                        </div>
                        <div class="album-title">
                            <h3 class="album-folder-title">{{ $album_link->album_name }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($album_links->total() > $items_amount_per_page)
            <div class="paginator">
                @if ($album_links->currentPage() == 1)
                    <span class="first-inactive"></span>
                @else
                    <a href="{{ $album_links->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                @endif
                @if ($album_links->currentPage() == 1)
                    <span class="previous-inactive"></span>
                @else
                    <a href="{{ $album_links->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                @endif
                <span class="pagination-info">{{ $album_links->currentPage() }} @lang('pagination.Of') {{ $album_links->lastPage() }}</span>
                @if ($album_links->currentPage() == $album_links->lastPage())
                    <span class="next-inactive"></span>
                @else
                    <a href="{{ $album_links->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                @endif
                @if ($album_links->currentPage() == $album_links->lastPage())
                    <span class="last-inactive"></span>
                @else
                    <a href="{{ $album_links->url($album_links->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                @endif
            </div>
        @endif
    @else
        <div class="empty-albums-text-wrapper">
            <p>@lang('folderContent.EmptySection')</p>
        </div>
    @endif
</article>
@stop