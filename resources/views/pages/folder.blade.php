@extends('app')

@section('content')

@if ($folders_and_articles_total_number > 0)
    @if ($articleAmount < 1)
        <article class="website-main-article articles-article-folders">
            <div>
                <h2>{{ $headTitle }}</h2>
            </div>
            <div class="external-folders-wrapper">
                <div class="folders-wrapper">       
                    @foreach ($folders_and_articles as $folder_or_article)
                        <div class="folder-item">
                            <div class="folder-body">
                                @if (App::isLocale('en'))
                                    <a href='/articles/{{ $folder_or_article->keyWord }}/page/1'>
                                        <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder_or_article->caption }}" class="article-folder">
                                    </a>
                                @else
                                    <a href='/ru/articles/{{ $folder_or_article->keyWord }}/page/1'>
                                        <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder_or_article->caption }}" class="article-folder">
                                    </a>
                                @endif
                            </div>
                            <div class="folder-title">
                                <h3 class="article-folder-title">{{ $folder_or_article->caption }}</h3>
                            </div>
                        </div>
                    @endforeach       
                </div>
            </div>
            @if ($folders_and_articles_total_number > $items_amount_per_page)
                <div class="paginator">
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
        </article>   
    @else
        <article class="website-main-article articles-article-folders-and-articles">
            <div>
                <h2>{{ $headTitle }}</h2>
            </div>
            <div class="folders-and-articles-wrapper">       
                @foreach ($folders_and_articles as $folder_or_article)
                    @if ($folder_or_article->type == 'folder')
                        <div class="article-folder-or-article">
                            @if (App::isLocale('en'))
                                <a href='/articles/{{ $folder_or_article->keyWord }}/page/1' class="article-folder-or-article-link">
                                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/regular_folder_small.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                                </a>
                            @else
                                <a href='/ru/articles/{{ $folder_or_article->keyWord }}/page/1' class="article-folder-or-article-link">
                                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/regular_folder_small.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="article-folder-or-article">
                            @if (App::isLocale('en'))
                                <a href='/articles/{{ $folder_or_article->keyWord }}' class="article-folder-or-article-link">
                                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                                </a>
                            @else
                                <a href='/ru/articles/{{ $folder_or_article->keyWord }}' class="article-folder-or-article-link">
                                    <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                                    <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach       
            </div>
            @if ($folders_and_articles_total_number > $items_amount_per_page)
                <div class="paginator">
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
        </article>
    @endif
@else
    <article class="website-main-article articles-article-folders">
        <div class="empty-folders-text-wrapper">
            <p>@lang('folderContent.EmptySection')</p>
        </div>
    </article> 
@endif   

@stop