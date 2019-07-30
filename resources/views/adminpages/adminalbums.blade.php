@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">
    <div class="admin-panel-albums-add-picture-album-wrapper">
        <div class="admin-panel-albums-add-picture-album-button">
            <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddAlbum')</a>
        </div>
    </div>         
    @if ($albums->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">
                @foreach ($albums as $album)
                    <div class="admin-panel-albums-picture-and-album-item">
                        <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                            <img src="{{ URL::asset('images/icons/album_folder.png') }}" class="admin-panel-albums-picture-and-album-picture">
                            <span class="admin-panel-albums-picture-and-album-title">{{ $album->album_name }}</span>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                            <div class="admin-panel-albums-picture-and-album-control-buttons">
                                <div class="admin-panel-albums-picture-and-album-control-button">
                                    <a href='albums/{{ $album->keyword }}/page/1' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                </div>
                                <div class="admin-panel-albums-picture-and-album-control-button">
                                    <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                </div>
                                <div class="admin-panel-albums-picture-and-album-control-button">
                                    <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if ($albums->total() > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('one_entity_paginator', ['items' => $albums])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop
