@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-keywords">
    <div class="admin-panel-keywords-add-keyword-button">
        <a href={{ App::isLocale('en') ? "/admin/keywords/create" : "/ru/admin/keywords/create" }} 
        class="admin-panel-keywords-add-keyword-button-link" data-fancybox data-type="iframe">
            @lang('keywords.AddKeyword')
        </a>   
    </div>
    @if ($keywords->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-keywords-external-keywords-wrapper">
            <div class="admin-panel-keywords-keywords-wrapper">
                <div class="admin-panel-keywords-keywords-header-wrapper">
                    <div class="admin-panel-keywords-keywords-header">
                        <h3>@lang('keywords.Keyword')</h3>
                    </div>
                    <div class="admin-panel-keywords-keywords-header">
                        <h3>@lang('keywords.Text')</h3>
                    </div>
                    <div class="admin-panel-keywords-keywords-header"></div>
                </div>
                @foreach ($keywords as $keyword)
                    <div class="admin-panel-keywords-keyword-wrapper">    
                        <div class="admin-panel-keywords-keyword">
                            <p>{{ $keyword->keyword }}</p>
                        </div>
                        <div class="admin-panel-keywords-keyword">
                            <p>{{ $keyword->text }}</p>
                        </div>
                        <div class="admin-panel-keywords-keyword admin-panel-keywords-keyword-control-buttons-wrapper">
                            <div class="admin-panel-keywords-keyword-control-buttons">
                                <div class="admin-panel-keywords-keyword-control-button admin-panel-keywords-keyword-edit-button">
                                    <a class="admin-panel-keywords-keyword-control-button-link admin-panel-keywords-keyword-edit-button-link" 
                                       data-fancybox data-type="iframe" href={{ App::isLocale('en') ? "keywords/".$keyword->keyword."/edit" : 
                                       "/ru/admin/keywords/".$keyword->keyword."/edit" }}>@lang('keywords.Edit')</a>
                                </div>
                                <div class="admin-panel-keywords-keyword-control-button">
                                    <a class="admin-panel-keywords-keyword-control-button-link admin-panel-keywords-keyword-delete-button-link"
                                       data-fancybox data-type="iframe" href={{ App::isLocale('en') ? "keywords/".$keyword->keyword : 
                                       "/ru/admin/keywords/".$keyword->keyword }}>@lang('keywords.Delete')</a>
                                </div>
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


