@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    @if ($folders->count() > 0)
        <div class="external-folders-wrapper">
            <div class="folders-wrapper">
                @foreach ($folders as $folder)
                    <div class="folder-item">
                        <div class="folder-body">
                            <a href='articles/{{ $folder->keyword }}/page/1'>
                                <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder->folder_name }}" class="article-folder">
                            </a>
                        </div>
                        <div class="folder-title">
                            <h3 class="article-folder-title">{{ $folder->folder_name }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($folders->total() > $items_amount_per_page)
        <div class="paginator">
            @if ($folders->currentPage() == 1)
               <span class="first-inactive"></span>
            @else
               <a href="{{ $folders->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
            @endif
            @if ($folders->currentPage() == 1)
               <span class="previous-inactive"></span>
            @else
               <a href="{{ $folders->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
            @endif
            <span class="pagination-info">{{ $folders->currentPage() }} @lang('pagination.Of') {{ $folders->lastPage() }}</span>
            @if ($folders->currentPage() == $folders->lastPage())
               <span class="next-inactive"></span>
            @else
               <a href="{{ $folders->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
            @endif
            @if ($folders->currentPage() == $folders->lastPage())
               <span class="last-inactive"></span>
            @else
               <a href="{{ $folders->url($folders->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
            @endif
        </div>
        @endif
    @else
        <div class="empty-folders-text-wrapper">
            <p>@lang('folderContent.EmptySection')</p>
        </div>
    @endif
</article>
@stop