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
        <div class="admin-panel-albums-add-picture-button">
            <a href={{ App::isLocale('en') ? "/admin/pictures/create/".$parent_keyword : "/ru/admin/pictures/create/".$parent_keyword }} 
                class="admin-panel-albums-add-picture-button-link" data-fancybox data-type="iframe">
                   @lang('keywords.AddPicture')
            </a>
        </div>
        @if ($nesting_level < 7)
            <div class="admin-panel-albums-add-album-button">
                <a href={{ App::isLocale('en') ? "/admin/albums/create/".$parent_keyword : "/ru/admin/albums/create/".$parent_keyword }} 
                class="admin-panel-albums-add-album-button-link" data-fancybox data-type="iframe">
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
                        <div class="admin-panel-albums-picture-and-album-picture-and-text-wrapper">
                            @if ($album_or_picture->type == 'album')
                                <a href="{{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/page/1" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/page/1" }}">
                                    <div class='admin-panel-albums-albums-picture'>
                                        <img src="{{ ($album_or_picture->isVisible==1) ? URL::asset('images/icons/album_folder.png') : 
                                                URL::asset('images/icons/album_folder_bnw.png') }}"
                                        class='admin-panel-albums-albums-picture-image'>
                                    </div>
                                    <div class='admin-panel-albums-albums-title'>
                                        <span class='admin-panel-albums-albums-title-text'>{{ $album_or_picture->caption }}</span>
                                    </div>
                                </a>
                            @elseif ($album_or_picture->type == 'picture')
                                <a href='{{ URL::asset($pathToFile.$album_or_picture->fileName) }}' 
                                        data-fancybox="group" data-caption="{{ $album_or_picture->caption }}" title="{{ $album_or_picture->caption }}" 
                                        class="admin-panel-albums-picture-control-button-link-open">
                                    <div class='admin-panel-albums-pictures-picture'>
                                        <img src="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}"
                                            class='admin-panel-albums-pictures-picture-image'  style="{{ ($album_or_picture->isVisible==1) ? 
                                            'opacity:1' : 'opacity:0.45' }}">
                                    </div>
                                    <div class='admin-panel-albums-pictures-title'>
                                        <span class='admin-panel-albums-pictures-title-text'>{{ $album_or_picture->caption }}</span>
                                    </div>
                                </a>
                            @endif
                        </div>
                        <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                            <div class="admin-panel-albums-picture-and-album-control-buttons">
                                @if ($album_or_picture->type == 'album')
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-albums-album-control-button-link-edit only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/edit/".$parent_keyword : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/edit/".$parent_keyword }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link 
                                            admin-panel-albums-album-control-button-link-edit" data-fancybox data-type="iframe">
                                            @lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-albums-album-control-button-link-delete only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/delete" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/delete" }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link 
                                            admin-panel-albums-album-control-button-link-delete" data-fancybox data-type="iframe">
                                            @lang('keywords.Delete')</a>
                                    </div>
                                @elseif ($album_or_picture->type == 'picture') 
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-albums-picture-control-button-link-edit only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/pictures/".$album_or_picture->keyWord."/edit/".$parent_keyword : 
                                            "/ru/admin/pictures/".$album_or_picture->keyWord."/edit/".$parent_keyword }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link 
                                            admin-panel-albums-picture-control-button-link-edit" data-fancybox data-type="iframe">
                                            @lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <!--We need to provide absolute path below as otherwise links are not working correctly -->
                                        <!--We need class admin-panel-albums-picture-control-button-link-delete only to identify edit button -->
                                        <a href={{ App::isLocale('en') ? "/admin/pictures/".$album_or_picture->keyWord."/delete" : 
                                            "/ru/admin/pictures/".$album_or_picture->keyWord."/delete" }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link 
                                            admin-panel-albums-picture-control-button-link-delete" data-fancybox data-type="iframe">
                                            @lang('keywords.Delete')</a>
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
