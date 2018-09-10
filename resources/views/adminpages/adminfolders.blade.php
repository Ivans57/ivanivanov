@extends('admin')

@section('admincontent')
<div class="admin-panel-main-path-wrapper">
    <div class="admin-panel-main-path">
        <p>This is main path panel.</p>
    </div>
</div>
<div class="admin-panel-main-article-wrapper">
    <article class="admin-panel-main-article">
        <h2 style="color: green;">{{ $headTitle }}</h2>
        <div>
            <a href=''>@lang('keywords.AddArticle')</a>
        </div>
        <div>
            <a href=''>@lang('keywords.AddFolder')</a>
        </div>
        <div>
            @if ($folders->count() > 0)
                @foreach ($folders as $folder)
                    <div class="folder-item">
                        <div class="folder-body">
                            <a href='articles/{{ $folder->keyword }}/page/1'>{{ $folder->folder_name }}</a>
                        </div>
                    </div>
                @endforeach
            @else
                <p>@lang('keywords.EmptySection')</p>
            @endif
            
            @if ($folders->total() > 16)
                <section class="paginator">
                    @if ($folders->currentPage() == 1)
                        <span class="first-inactive">@lang('pagination.ToFirstPage') | </span>
                    @else
                        <a href="{{ $folders->url(1) }}" class="first-active">@lang('pagination.ToFirstPage')</a><span> | </span>
                    @endif
                    @if ($folders->currentPage() == 1)
                        <span class="previous-inactive">@lang('pagination.ToPreviousPage')</span>
                    @else
                        <a href="{{ $folders->previousPageUrl() }}" class="previous-active">@lang('pagination.ToPreviousPage')</a>
                    @endif
                        <span class="pagination-info">({{ $folders->currentPage() }} @lang('pagination.Of') {{ $folders->lastPage() }})</span>
                    @if ($folders->currentPage() == $folders->lastPage())
                        <span class="next-inactive">@lang('pagination.ToNextPage') | </span>
                    @else
                        <a href="{{ $folders->nextPageUrl() }}" class="next-active">@lang('pagination.ToNextPage')</a><span> | </span>
                    @endif
                    @if ($folders->currentPage() == $folders->lastPage())
                        <span class="last-inactive">@lang('pagination.ToLastPage')</span>
                    @else
                        <a href="{{ $folders->url($folders->lastPage()) }}" class="last-active">@lang('pagination.ToLastPage')</a>
                    @endif
                </section>
            @endif
        </div>
    </article>
</div>
@stop
