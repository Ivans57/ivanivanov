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
                    <a href={{ App::isLocale('en') ? "/admin/article/create/".$parent_keyword."/".
                                                      $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first : 
                                                      "/ru/admin/article/create/".$parent_keyword."/".
                                                      $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first }} 
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
        <div class="admin-panel-keywords-search">
            {!! Form::text('folder_search', null, 
                    ['class' => 'admin-panel-keywords-search-input', 
                     'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'folder_search', 'id' => 'folder_search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                    ['class' => 'admin-panel-keywords-search-button', 'id' => 'folder_search_button', 'title' => Lang::get('keywords.Search'), 
                     'data-localization' => App::isLocale('en') ? 'en' : 'ru' ]) !!}
        </div>
    @endif
    <!-- The class below is required only for JavaScript purposes.-->
    <div class="admin-panel-articles-content">
        @include('adminpages.folders.adminfolder_content')
    </div>   
</article>

@stop
