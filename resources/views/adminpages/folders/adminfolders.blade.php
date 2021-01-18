@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-articles">
    <div class="admin-panel-articles-control-buttons">
        <div class="admin-panel-articles-add-article-folder-wrapper">
            <div class="admin-panel-articles-add-folder-button">
                <a href='{{ App::isLocale('en') ? "/admin/articles/create/".$parent_keyword : "/ru/admin/articles/create/".$parent_keyword }}' 
                   class="admin-panel-articles-add-folder-button-link" data-fancybox data-type="iframe">@lang('keywords.AddFolder')</a>
            </div>
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
    @if ($all_folders_count > 1)
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
        @include('adminpages.folders.adminfolders_content')
    </div>
</article>

@stop
