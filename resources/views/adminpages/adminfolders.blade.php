@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-articles-add-article-folder-wrapper">
        <div class="admin-panel-articles-add-article-folder-button">
            <a href='#' class="admin-panel-articles-add-article-folder-button-link">@lang('keywords.AddFolder')</a>
        </div>
    </div>         
    @if ($folders->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-articles-external-articles-and-folders-wrapper">
            <div class="admin-panel-articles-articles-and-folders-wrapper">
                @foreach ($folders as $folder)
                    <div class="admin-panel-articles-article-and-folder-item">
                        <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                            <div>
                                <img src="{{ URL::asset('images/icons/regular_folder_small.png') }}">
                            </div>
                            <div class="admin-panel-articles-article-and-folder-title">
                                <p>{{ $folder->folder_name }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-articles-article-and-folder-control-buttons-wrapper">
                            <div class="admin-panel-articles-article-and-folder-control-buttons">
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='articles/{{ $folder->keyword }}/page/1' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Open')</a>
                                </div>
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Edit')</a>
                                </div>
                                <div class="admin-panel-articles-article-and-folder-control-button">
                                    <a href='#' class="admin-panel-articles-article-and-folder-control-button-link">@lang('keywords.Delete')</a>
                                </div>
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
