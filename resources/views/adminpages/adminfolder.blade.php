@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    @if ($folders_and_articles_total_number > 0)
        <div>
            <h2>{{ $headTitle }}</h2>
        </div>
        <div class="admin-panel-articles-add-article-folder-wrapper">
            <div class="admin-panel-articles-add-article-folder-button">
                <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddArticle')</a>
            </div>
            <div class="admin-panel-articles-add-article-folder-button">
                <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddFolder')</a>
            </div>
        </div>
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">          
                @foreach ($folders_and_articles as $folder_or_article)
                    @if ($folder_or_article->type == 'folder')
                        <div class="admin-panel-articles-article-and-folder-item">
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div class="admin-panel-articles-article-and-folder-picture">
                                    <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->caption }}</p>
                                </div>
                            </div>
                            <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                                <div class="admin-panel-articles-article-and-folder-control-buttons">
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        @if (App::isLocale('en'))
                                            <a href='/admin/articles/{{ $folder_or_article->keyWord }}/page/1' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Open')</a>
                                        @else
                                            <a href='/ru/admin/articles/{{ $folder_or_article->keyWord }}/page/1' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Open')</a>
                                        @endif
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
                    @endif
                    @if ($folder_or_article->type == 'article')
                        <div class="admin-panel-articles-article-and-folder-item">
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div class="admin-panel-articles-article-and-folder-picture">
                                    <img src="{{ URL::asset('images/icons/article.png') }}">
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->caption }}</p>
                                </div>
                            </div>
                            <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                                <div class="admin-panel-articles-article-and-folder-control-buttons">
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href='#' class="admin-panel-articles-article-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href='#' class="admin-panel-articles-article-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach     
            </div>
        </div>
        @if ($folders_and_articles_total_number > $items_amount_per_page)
            <div class="admin-panel-paginator">
                @if ($folders_and_articles_current_page == 1)
                    <span class="first-inactive"></span>
                @else
                    <a href="1" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                @endif
                @if ($folders_and_articles_current_page == 1)
                    <span class="previous-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles_previous_page }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                @endif
                    <span class="pagination-info">{{ $folders_and_articles_current_page }} @lang('pagination.Of') {{ $folders_and_articles_number_of_pages }}</span>
                @if ($folders_and_articles_current_page == $folders_and_articles_number_of_pages)
                    <span class="next-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles_next_page }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                @endif
                @if ($folders_and_articles_current_page == $folders_and_articles_number_of_pages)
                    <span class="last-inactive"></span>
                @else
                    <a href="{{ $folders_and_articles_number_of_pages }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                @endif
            </div>
        @endif
    @else
        <div class="admin-panel-articles-add-article-folder-wrapper">
            <div class="admin-panel-articles-add-article-folder-button">
                <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddArticle')</a>
            </div>
            <div class="admin-panel-articles-add-article-folder-button">
                <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddFolder')</a>
            </div>
        </div>
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
