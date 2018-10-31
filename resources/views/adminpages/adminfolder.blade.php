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
        <div class="admin-panel-add-article-folder-wrapper">
            <div class="admin-panel-add-article-folder-button">
                <a href='#' class="admin-panel-add-article-folder-button-link">@lang('keywords.AddArticle')</a>
            </div>
            <div class="admin-panel-add-article-folder-button">
                <a href='#' class="admin-panel-add-article-folder-button-link">@lang('keywords.AddFolder')</a>
            </div>
        </div>         
        @if ($folders_and_articles->count() > 0)
            <div class="admin-panel-external-article-folders-wrapper">
                <div class="admin-panel-article-folders-wrapper">
                    @foreach ($folders_and_articles as $folder_and_article)
                        <div class="admin-panel-folder-item">
                            <div class="admin-panel-folder-title-and-picture-wrapper">
                                <div class="admin-panel-folder-picture">
                                    <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                                </div>
                                <div class="admin-panel-folder-title">
                                    <p>{{ $folder_and_article->caption }}</p>
                                </div>
                            </div>
                            <div class="admin-panel-folder-buttons-wrapper">
                                <div class="admin-panel-folder-buttons">
                                    <div class="admin-panel-article-folder-control-button">
                                        <a href='articles/{{ $folder_and_article->keyword }}/page/1' class="admin-panel-article-folder-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-article-folder-control-button">
                                        <a href='#' class="admin-panel-article-folder-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-article-folder-control-button">
                                        <a href='#' class="admin-panel-article-folder-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="admin-panel-empty-folders-text-wrapper">
                <p>@lang('keywords.EmptySection')</p>
            </div>
        @endif  
        @if ($folders_and_articles->total() > 25)
            <div class="admin-panel-paginator">
                @if ($folders_and_articles->currentPage() == 1)
                    <span class="first-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                @endif
                @if ($folders_and_articles->currentPage() == 1)
                    <span class="previous-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                @endif
                <span class="pagination-info">{{ $folders_and_articles->currentPage() }} @lang('pagination.Of') {{ $folders_and_articles->lastPage() }}</span>
                @if ($folders_and_articles->currentPage() == $folders_and_articles->lastPage())
                    <span class="next-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                @endif
                @if ($folders_and_articles->currentPage() == $folders_and_articles->lastPage())
                    <span class="last-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles->url($folders_and_articles->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                @endif
            </div>
        @endif
    </article>
</div>
@stop
