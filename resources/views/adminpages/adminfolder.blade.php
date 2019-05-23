@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/articles" : "/ru/admin/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($folderParents > 0)    
            @foreach ($folderParents as $folderParent)
                <a href={{ App::isLocale('en') ? "/admin/articles/".$folderParent->keyWord."/page/1" : 
                   "/ru/admin/articles/".$folderParent->keyWord."/page/1" }} class="path-panel-text">{{ $folderParent->folderName }}</a>
                <span class="path-panel-text"> /</span>
            @endforeach
        @endif
    </div>
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
    @if ($total_number_of_items > 0)
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">          
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="admin-panel-articles-article-and-folder-item">
                        <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                            <div>
                                <img src="{{ $folder_or_article->type == 'folder' ? URL::asset('images/icons/regular_folder_small.png') : URL::asset('images/icons/article.png') }}">
                            </div>
                            <div class="admin-panel-articles-article-and-folder-title">
                                <p>{{ $folder_or_article->caption }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                            <div class="admin-panel-articles-article-and-folder-control-buttons">                                    
                                @if ($folder_or_article->type == 'folder')                                    
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/page/1" : 
                                            "/ru/admin/articles/".$folder_or_article->keyWord."/page/1" }} 
                                            class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Delete')</a>
                                    </div>                                    
                                @else                                    
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href='#' class="admin-panel-articles-article-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href='#' class="admin-panel-articles-article-control-button-link">@lang('keywords.Delete')</a>
                                    </div>                                    
                                @endif                                   
                            </div>
                        </div>
                    </div>
                @endforeach     
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <div class="admin-panel-paginator">
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
    @else
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
