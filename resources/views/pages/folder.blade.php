@extends('app')

@section('content')

<article class="{{ $articleAmount < 1 ? "website-main-article articles-article-folders" : "website-main-article articles-article-folders-and-articles" }}">       
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/articles" : "/ru/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>             
        @if ($folderParents > 0)                
            <span class="path-panel-text"> /</span>
            @foreach ($folderParents as $folderParent)
                <a href={{ App::isLocale('en') ? "/articles/".$folderParent->keyWord."/page/1" : 
                    "/ru/articles/".$folderParent->keyWord."/page/1" }} class="path-panel-text">{{ $folderParent->folderName }}</a>
                <span class="path-panel-text"> /</span>
            @endforeach                
        @endif
    </div>
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
    @if ($total_number_of_items > 0)   
        @if ($articleAmount < 1)       
            <div class="external-folders-wrapper">
                <div class="folders-wrapper">       
                    @foreach ($folders_and_articles as $folder_or_article)
                        <div class="folder-item">
                            <div class="folder-body">
                                <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : 
                                    "/ru/articles/".$folder_or_article->keyWord."/page/1" }}>
                                        <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder_or_article->caption }}" class="article-folder">
                                </a>                   
                            </div>
                            <div class="folder-title">
                                <h3 class="article-folder-title">{{ $folder_or_article->caption }}</h3>
                            </div>
                        </div>
                    @endforeach       
                </div>
            </div>
            @if ($total_number_of_items > $items_amount_per_page)
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
        @else
            <div class="folders-and-articles-wrapper">       
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="article-folder-or-article">                                               
                        <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : "/ru/articles/".$folder_or_article->keyWord."/page/1" }} class="article-folder-or-article-link">
                            <div class="article-folder-or-article-picture"><img src="{{ $folder_or_article->type == 'folder' ? URL::asset('images/icons/regular_folder_small.png') : URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                            <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                        </a>                                                                                                                
                    </div>
                @endforeach       
            </div>
            @if ($total_number_of_items > $items_amount_per_page)
                <div class="paginator">
                    @if ($current_page == 1)
                        <span class="first-inactive"></span>
                    @else
                        <a href="1" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                    @endif
                    @if ($current_page == 1)
                        <span class="previous-inactive"></span>
                    @else
                        <a href="{{ $previous_page }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                    @endif
                        <span class="pagination-info">{{ $current_page }} @lang('pagination.Of') {{ $number_of_pages }}</span>
                    @if ($current_page == $number_of_pages)
                        <span class="next-inactive"></span>
                    @else
                        <a href="{{ $next_page }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                    @endif
                    @if ($current_page == $number_of_pages)
                        <span class="last-inactive"></span>
                    @else
                        <a href="{{ $number_of_pages }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                    @endif
                </div>
            @endif      
        @endif
    @else
        <div class="empty-folders-text-wrapper">
            <p>@lang('folderContent.EmptySection')</p>
        </div>     
    @endif   
</article>
@stop