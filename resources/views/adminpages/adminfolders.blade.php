@extends('admin')

@section('admincontent')
<div class="admin-panel-main-path-wrapper">
    <div class="admin-panel-main-path">
        <p>This is main path panel.</p>
    </div>
</div>
<!-- "admin-panel-main-article-wrapper" we might not need if we remove the path section above -->
<div class="admin-panel-main-article-wrapper">
    <article class="admin-panel-main-article">
        <div style="background-color: red;" class="admin-panel-add-article-or-folder-button-wrapper">
            <div>
                <a href=''>@lang('keywords.AddArticle')</a>
            </div>
            <div>
                <a href=''>@lang('keywords.AddFolder')</a>
            </div>
        </div>     
        @if ($folders->count() > 0)
            <div class="admin-panel-external-folders-wrapper">
                <div style="background-color: green;" class="admin-panel-folders-wrapper">
                    @foreach ($folders as $folder)
                        <div class="folder-item">
                            <div class="folder-body">
                                <a href='articles/{{ $folder->keyword }}/page/1'>{{ $folder->folder_name }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div style="background-color: green;" class="admin-panel-empty-folders-text-wrapper">
                <p>@lang('keywords.EmptySection')</p>
            </div>
        @endif
            
        @if ($folders->total() > 25)
            <div class="admin-panel-paginator">
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
    </article>
</div>
@stop
