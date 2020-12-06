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
    <div>
        {!! Form::text('keyword_search', null, 
            ['class' => 'some-class', 
             'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'keyword_search', 'id' => 'keyword_search']) !!}
        {!! Form::button('<span class="glyphicon glyphicon-search"></span>', 
                ['class' => 'some-class', 'id' => 'keyword_search_button', 'title' => Lang::get('keywords.FindInDataBase') ]) !!}
    </div>
    @if ($keywords->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-keywords-external-keywords-wrapper">
            <div class="admin-panel-keywords-keywords-wrapper">
                @include('adminpages.adminkeywords_table')
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


