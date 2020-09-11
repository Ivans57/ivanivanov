@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-articles-cotrol-buttons">
        <div class="admin-panel-articles-add-article-folder-wrapper">
            <div class="admin-panel-articles-add-folder-button">
                <a href='articles/create/{{ $parent_keyword }}' class="admin-panel-articles-add-folder-button-link"
                   data-fancybox data-type="iframe">@lang('keywords.AddFolder')</a>
            </div>
        </div>
        <div class="admin-panel-articles-article-and-folder-control-buttons">
            <div>    
                {!! Form::button(Lang::get('keywords.Edit'), 
                [ 'class' => 'admin-panel-articles-article-and-folder-control-button 
                admin-panel-articles-article-and-folder-control-button-disabled', 
                'id' => 'button_edit', 'disabled' ]) !!}
            </div>
            <div>
                {!! Form::button(Lang::get('keywords.Delete'), 
                [ 'class' => 'admin-panel-articles-article-and-folder-control-button 
                admin-panel-articles-article-and-folder-control-button-disabled', 
                'id' => 'button_delete', 'disabled' ]) !!}
            </div>           
        </div>
    </div>
    @if ($folders->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">
                <div class="admin-panel-articles-article-and-folder-header-row">
                    <div class="admin-panel-articles-article-and-folder-header-field" title="Select all">
                        {!! Form::checkbox('all_items_select', 'value', false, ['id' => 'all_items_select']); !!}
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <p>Name</p>
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <p>Date and Time when created</p>
                    </div>
                    <div class="admin-panel-articles-article-and-folder-header-field">
                        <p>Date and Time when updated</p>
                    </div>
                </div>
                @foreach ($folders as $folder)
                    <div class="admin-panel-articles-article-and-folder-body-row">
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            {!! Form::checkbox('item_select', 1, false, 
                            ['data-keyword' => $folder->keyword, 'data-parent_keyword' => $parent_keyword,
                             'data-entity_type' => 'directory',  'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                             'class' => 'admin-panel-articles-article-and-folder-checkbox']); !!}
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            <a href="articles/{{ $folder->keyword }}/page/1">
                                <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                    <div>
                                        <img src="{{ ($folder->is_visible==1) ? URL::asset('images/icons/regular_folder_small.png') : 
                                                    URL::asset('images/icons/regular_folder_small_bnw.png') }}">                                
                                    </div>
                                    <div class="admin-panel-articles-article-and-folder-title">
                                        <p>{{ $folder->folder_name }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            <p>{{ $folder->created_at }}</p>
                        </div>
                        <div class="admin-panel-articles-article-and-folder-body-field">
                            <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                                <p>{{ $folder->updated_at }}</p>
                            </div>
                        </div>    
                    </div>
                @endforeach
            </div>
        </div>
        @if ($folders->total() > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('one_entity_paginator', ['items' => $folders])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-articles-empty-folders-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop
