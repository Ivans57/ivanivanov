@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/albums" : "/ru/admin/albums" }} class="path-panel-text">@lang('keywords.Albums')</a>
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
    <div class="admin-panel-albums-add-picture-album-wrapper">
        <div class="admin-panel-albums-add-picture-album-button">
            <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddPicture')</a>
        </div>
        @if ($nesting_level < 7)
            <div class="admin-panel-albums-add-picture-album-button">
                <a href={{ App::isLocale('en') ? "/admin/albums/create/".$parent_keyword : "/ru/admin/albums/create/".$parent_keyword }} 
                class="admin-panel-albums-add-picture-album-button-link" data-fancybox data-type="iframe">
                   @lang('keywords.AddAlbum')
                </a>
            </div>
        @endif
    </div>
    @if ($total_number_of_items > 0)
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">          
                @foreach ($albums_and_pictures as $album_or_picture)   
                    <div class="admin-panel-albums-picture-and-album-item">
                        <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                            <img src="{{ $album_or_picture->type == 'album' ? 
                                URL::asset('images/icons/album_folder.png') : URL::asset('images/pages/albums/'.$albumName.'/'.$album_or_picture->keyWord.$album_or_picture->fileExtension) }}"
                                 class="admin-panel-albums-picture-and-album-picture">
                            <span class="admin-panel-albums-picture-and-album-title">{{ $album_or_picture->caption }}</span>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                            <div class="admin-panel-albums-picture-and-album-control-buttons">
                                @if ($album_or_picture->type == 'album')
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/page/1" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/page/1" }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-albums-picture-and-album-control-button-link-edit only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/edit/".$parent_keyword : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/edit/".$parent_keyword }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link admin-panel-albums-picture-and-album-control-button-link-edit" data-fancybox data-type="iframe">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/delete" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/delete" }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link admin-panel-albums-picture-and-album-control-button-link-delete" data-fancybox data-type="iframe">@lang('keywords.Delete')</a>
                                    </div>
                                @elseif ($album_or_picture->type == 'picture') 
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
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
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
