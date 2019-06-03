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
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
