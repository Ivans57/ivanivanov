@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-keywords">
    <div class="admin-panel-keywords-control-buttons">
        <div class="admin-panel-keywords-add-keyword-wrapper">
            <div class="admin-panel-keywords-add-keyword-button">
                <a href={{ App::isLocale('en') ? "/admin/keywords/create" : "/ru/admin/keywords/create" }} 
                class="admin-panel-keywords-add-keyword-button-link" data-fancybox data-type="iframe">
                    @lang('keywords.AddKeyword')
                </a>   
            </div>
        </div>
        <div class="admin-panel-keywords-edit-delete-control-buttons">
            <div>    
                {!! Form::button(Lang::get('keywords.Edit'), 
                [ 'class' => 'admin-panel-keywords-edit-delete-control-button 
                admin-panel-keywords-edit-delete-control-button-disabled', 
                'id' => 'keywords_button_edit', 'disabled' ]) !!}
            </div>
            <div>
                {!! Form::button(Lang::get('keywords.Delete'), 
                [ 'class' => 'admin-panel-keywords-edit-delete-control-button 
                admin-panel-keywords-edit-delete-control-button-disabled', 
                'id' => 'keywords_button_delete', 'disabled' ]) !!}
            </div>           
        </div>
    </div>
    @if ($keywords->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-keywords-external-keywords-wrapper">
            <div class="admin-panel-keywords-keywords-wrapper">
                <div class="admin-panel-keywords-keywords-header-row">
                    <div class="admin-panel-keywords-keywords-header-field" id="keywords_all_items_select_wrapper" 
                         title='{{ Lang::get("keywords.SelectAll") }}' 
                         data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                        {!! Form::checkbox('keywords_all_items_select', 'value', false, ['id' => 'keywords_all_items_select', 
                        'class' => 'admin-panel-keywords-keywords-header-checkbox']); !!}
                    </div>
                    <div class="admin-panel-keywords-keywords-header-field">
                        <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                            <div class="admin-panel-keywords-keywords-header-text">
                                <h3>@lang('keywords.Keyword')</h3>
                            </div>
                            <div class="admin-panel-keywords-keywords-header-caret">
                                <span class="glyphicon glyphicon-triangle-bottom"></span>
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-header-field">
                        <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                            <div class="admin-panel-keywords-keywords-header-text">
                                <h3>@lang('keywords.Text')</h3>
                            </div>
                            <div class="admin-panel-keywords-keywords-header-caret">
                                <span class="glyphicon glyphicon-triangle-bottom"></span>
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-header-field">
                        <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                            <div class="admin-panel-keywords-keywords-header-text">
                                <h3>@lang('keywords.Section')</h3>
                            </div>
                            <div class="admin-panel-keywords-keywords-header-caret">
                                <span class="glyphicon glyphicon-triangle-bottom"></span>
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-header-field">
                        <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                            <div class="admin-panel-keywords-keywords-header-text">
                                <h3>@lang('keywords.DateAndTimeCreated')</h3>
                            </div>
                            <div class="admin-panel-keywords-keywords-header-caret">
                                <span class="glyphicon glyphicon-triangle-bottom"></span>
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-header-field">
                        <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                            <div class="admin-panel-keywords-keywords-header-text">
                                <h3>@lang('keywords.DateAndTimeUpdate')</h3>
                            </div>
                            <div class="admin-panel-keywords-keywords-header-caret">
                                <span class="glyphicon glyphicon-triangle-bottom"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($keywords as $keyword)
                    <div class="admin-panel-keywords-keywords-body-row"> 
                        <div class="admin-panel-keywords-keywords-body-field">
                            {!! Form::checkbox('item_select', 1, false, 
                            ['data-keyword' => $keyword->keyword, 'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                             'class' => 'admin-panel-keywords-keywords-checkbox']); !!}
                        </div>
                        <div class="admin-panel-keywords-keywords-body-field">
                            <p>{{ $keyword->keyword }}</p>
                        </div>
                        <div class="admin-panel-keywords-keywords-body-field">
                            <p>{{ $keyword->text }}</p>
                        </div>
                        <div class="admin-panel-keywords-keywords-body-field">
                            <p>{{ $keyword->section }}</p>
                        </div>
                        <div class="admin-panel-keywords-keywords-body-field">
                            <div class="admin-panel-keywords-keywords-body-field-content">
                                <p>{{ $keyword->created_at }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-keywords-keywords-body-field">
                            <div class="admin-panel-keywords-keywords-body-field-content">
                                <p>{{ $keyword->updated_at }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>        
        </div>
        @if ($keywords->total() > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('one_entity_paginator', ['items' => $keywords])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-keywords-empty-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop


