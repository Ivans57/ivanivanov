@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/articles" : "/ru/admin/articles" }} class="path-panel-text">@lang('keywords.Articles')</a>
        <span class="path-panel-text"> /</span>
        @if ($parents > 0)    
            <!--The component below is based on paginator component-->
            @component('path_panel', ['parents' => $parents, 'is_admin_panel' => true])
                @slot('section')
                    {{ $section }}
                @endslot
            @endcomponent
        @endif
    </div>
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>
    <div class="admin-panel-articles-add-article-folder-wrapper">
        <div class="admin-panel-articles-add-article-button">
            <a href={{ App::isLocale('en') ? "/admin/article/create/".$parent_keyword : "/ru/admin/article/create/".$parent_keyword }} 
                class="admin-panel-articles-add-article-button-link">
                   @lang('keywords.AddArticle')
            </a>
        </div>
        @if ($nesting_level < 7)
            <div class="admin-panel-articles-add-folder-button">
                <a href={{ App::isLocale('en') ? "/admin/articles/create/".$parent_keyword : "/ru/admin/articles/create/".$parent_keyword }} 
                class="admin-panel-articles-add-folder-button-link" data-fancybox data-type="iframe">
                   @lang('keywords.AddFolder')
                </a>
            </div>
        @endif
    </div>
    @if ($total_number_of_items > 0)
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">          
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="admin-panel-articles-article-and-folder-item">
                        @if ($folder_or_article->type == 'folder')
                            <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/page/1" : 
                                        "/ru/admin/articles/".$folder_or_article->keyWord."/page/1" }}>
                                <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                    <div>
                                        <img src="{{ ($folder_or_article->isVisible==1) ? 
                                            URL::asset('images/icons/regular_folder_small.png') : URL::asset('images/icons/regular_folder_small_bnw.png') }}">
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-title">
                                        <p>{{ $folder_or_article->caption }}</p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <a href={{ App::isLocale('en') ? "/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord : 
                                        "/ru/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord }}>
                                <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                    <div>
                                        <img src="{{ URL::asset('images/icons/article.png') }}" style="{{ ($folder_or_article->isVisible==1) ? 
                                            'opacity:1' : 'opacity:0.45' }}">
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-title">
                                        <p>{{ $folder_or_article->caption }}</p>
                                    </div>
                                </div>
                            </a>
                        @endif
                        <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                            <div class="admin-panel-articles-article-and-folder-control-buttons">                                    
                                @if ($folder_or_article->type == 'folder')                                    
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-articles-folder-control-button-link-edit only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/edit/".$parent_keyword : 
                                            "/ru/admin/articles/".$folder_or_article->keyWord."/edit/".$parent_keyword }} 
                                            class="admin-panel-articles-article-and-folder-control-button-link 
                                            admin-panel-articles-folder-control-button-link-edit" data-fancybox data-type="iframe">
                                            @lang('keywords.Edit')
                                        </a>
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/delete" : 
                                            "/ru/admin/articles/".$folder_or_article->keyWord."/delete" }} 
                                            class="admin-panel-articles-article-and-folder-control-button-link 
                                            admin-panel-articles-folder-control-button-link-delete" data-fancybox data-type="iframe">
                                            @lang('keywords.Delete')
                                        </a>
                                    </div>                                    
                                @else
                                    <!-- Delete buttons for folders and articles will have to separate ...-delete classes,
                                    because these two buttons have to draw different size windows.-->
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord : 
                                            "/ru/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord }}
                                            class="admin-panel-articles-article-control-button-link">
                                            @lang('keywords.Edit')
                                        </a>
                                    </div>
                                    <div class="admin-panel-articles-article-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/article/".$folder_or_article->keyWord."/delete" : 
                                            "/ru/admin/article/".$folder_or_article->keyWord."/delete" }} 
                                            class="admin-panel-articles-article-control-button-link 
                                            admin-panel-articles-article-control-button-link-delete" data-fancybox data-type="iframe">
                                            @lang('keywords.Delete')
                                        </a>
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
