@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-articles">  
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
        <div class="admin-panel-articles-control-buttons">
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
            <div class="admin-panel-articles-article-and-folder-control-buttons">
                <div>    
                    {!! Form::button(Lang::get('keywords.Edit'), 
                    [ 'class' => 'admin-panel-articles-article-and-folder-control-button 
                    admin-panel-articles-article-and-folder-control-button-disabled', 
                    'id' => 'articles_button_edit', 'disabled' ]) !!}
                </div>
                <div>
                    {!! Form::button(Lang::get('keywords.Delete'), 
                    [ 'class' => 'admin-panel-articles-article-and-folder-control-button 
                    admin-panel-articles-article-and-folder-control-button-disabled', 
                    'id' => 'articles_button_delete', 'disabled' ]) !!}
                </div>
            </div>
        </div>
    </div>
    @if ($allArticlesAmount > 0 || $allFoldersAmount > 0)
        <div class="admin-panel-articles-sorting">
            {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                           ['class' => 'admin-panel-articles-sorting-label']); !!}
            {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                              ['id' => 'show_only_visible', 'class' => 'admin-panel-articles-sorting-controls', 
                              'data-localization' => (App::isLocale('en') ? 'en' : 'ru'),
                              'data-section' => $section, 'data-is_level_zero' => '0', 'data-parent_keyword' => $parent_keyword,
                              'data-old_sorting_method_and_mode' => $sorting_method_and_mode,
                              'data-old_directories_or_files_first' => $directories_or_files_first]); !!}                              
            @if ($articleAmount > 0 && $folderAmount > 0)                   
                {!! Form::label('folders_first', Lang::get('keywords.FoldersFirst').':', 
                               ['class' => 'admin-panel-articles-sorting-label']) !!}               
                {!! Form::radio('directories_or_files_first', 'folders_first', 
                               (($directories_or_files_first === 'folders_first') ? true : false), ['id' => 'folders_first', 
                                'class' => 'admin-panel-articles-sorting-controls']); !!}                    
                {!! Form::label('articles_first', Lang::get('keywords.ArticlesFirst').':', 
                               ['class' => 'admin-panel-articles-sorting-label']) !!}               
                {!! Form::radio('directories_or_files_first', 'articles_first', 
                               (($directories_or_files_first === 'articles_first') ? true : false), ['id' => 'articles_first', 
                                'class' => 'admin-panel-articles-sorting-controls']); !!}                    
            @endif     
        </div>
    @endif
    @if ($total_number_of_items > 0)
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">
                <div class="admin-panel-articles-article-and-folder-header-row">
                    <div class="admin-panel-articles-article-and-folder-header-field" id="articles_all_items_select_wrapper" 
                         title='{{ Lang::get("keywords.SelectAll") }}' 
                         data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                        {!! Form::checkbox('articles_all_items_select', 'value', false, ['id' => 'articles_all_items_select', 
                        'class' => 'admin-panel-articles-article-and-folder-header-checkbox']); !!}
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                            <div class="admin-panel-articles-article-and-folder-header-text">
                                <p>@lang('keywords.Name')</p>
                            </div>
                            <div class="admin-panel-articles-article-and-folder-header-caret">
                                @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_name" data-sorting_mode="desc" title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_name" data-sorting_mode="asc" title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                            <div class="admin-panel-articles-article-and-folder-header-text">
                                <p>@lang('keywords.DateAndTimeCreated')</p>
                            </div>
                            <div class="admin-panel-articles-article-and-folder-header-caret">
                                @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_creation" data-sorting_mode="desc" 
                                        title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_creation" data-sorting_mode="asc" 
                                        title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                            <div class="admin-panel-articles-article-and-folder-header-text">
                                <p>@lang('keywords.DateAndTimeUpdate')</p>
                            </div>
                            <div class="admin-panel-articles-article-and-folder-header-caret">
                                @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_update" data-sorting_mode="desc" 
                                        title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                        "admin-panel-articles-article-and-folder-header-caret-used" : 
                                        "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                        id="sort_by_update" data-sorting_mode="asc" 
                                        title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($folders_and_articles as $folder_or_article)
                    <div class="admin-panel-articles-article-and-folder-body-row">
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            @if ($folder_or_article->type == 'folder')
                                {!! Form::checkbox('item_select', 1, false, 
                                ['data-keyword' => $folder_or_article->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                 'data-entity_type' => 'directory', 'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                 'class' => 'admin-panel-articles-article-and-folder-checkbox' ]); !!}
                            @else
                                {!! Form::checkbox('item_select', 1, false, 
                                ['data-keyword' => $folder_or_article->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                 'data-entity_type' => 'file',  'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                 'class' => 'admin-panel-articles-article-and-folder-checkbox' ]) !!}
                            @endif
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            @if ($folder_or_article->type == 'folder')
                                <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/page/1" : 
                                            "/ru/admin/articles/".$folder_or_article->keyWord."/page/1" }}>
                                    <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                        <div>
                                            <img src="{{ ($folder_or_article->isVisible==1) ? 
                                                URL::asset('images/icons/regular_folder_small.png') : 
                                                        URL::asset('images/icons/regular_folder_small_bnw.png') }}">
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
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            <div class="admin-panel-articles-article-and-folder-body-field-content">
                                <p>{{ $folder_or_article->createdAt }}</p>    
                            </div>    
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            <div class="admin-panel-articles-article-and-folder-body-field-content">
                                <p>{{ $folder_or_article->updatedAt }}</p>
                            </div>
                        </div>    
                    </div>
                @endforeach     
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                       'parent_keyword' => $parent_keyword, 'sorting_mode' => $sorting_mode, 'is_admin_panel' => $is_admin_panel])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
