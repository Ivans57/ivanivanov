@extends('app')

@section('content')

<article class="{{ $articleAmount < 1 ? "website-main-article articles-article-folders" : "website-main-article articles-article-folders-and-articles" }}">       
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/articles" : "/ru/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent                
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
                <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
                @component('multy_entity_paginator', ['pagination_info' => $pagination_info])
                @endcomponent
            @endif        
        @else
            <div class="folders-and-articles-wrapper">       
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="article-folder-or-article">
                        @if ($folder_or_article->type == 'folder')
                            <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord."/page/1" : "/ru/articles/".$folder_or_article->keyWord."/page/1" }} class="article-folder-or-article-link">
                                <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/regular_folder_small.png')}}" alt="{{ $folder_or_article->caption }}"></div>
                                <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                            </a>
                        @else
                            <a href={{ App::isLocale('en') ? "/articles/".$folder_or_article->keyWord : "/ru/articles/".$folder_or_article->keyWord }} class="article-folder-or-article-link">
                                <div class="article-folder-or-article-picture"><img src="{{ URL::asset('images/icons/article.png') }}" alt="{{ $folder_or_article->caption }}"></div>
                                <div class="article-folder-or-article-title"><p>{{ $folder_or_article->caption }}</p></div>
                            </a>
                        @endif
                    </div>
                @endforeach       
            </div>
            @if ($total_number_of_items > $items_amount_per_page)
                <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
                @component('multy_entity_paginator', ['pagination_info' => $pagination_info])
                @endcomponent
            @endif      
        @endif
    @else
        <div class="empty-folders-text-wrapper">
            <p>@lang('folderContent.EmptySection')</p>
        </div>     
    @endif   
</article>
@stop