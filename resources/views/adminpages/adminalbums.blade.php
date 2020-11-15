@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-albums">
    <div class="admin-panel-albums-control-buttons">
        <div class="admin-panel-albums-add-picture-album-wrapper">
            <div class="admin-panel-albums-add-album-button">
                <a href='{{ App::isLocale('en') ? "/admin/albums/create/".$parent_keyword : "/ru/admin/albums/create/".$parent_keyword }}' 
                   class="admin-panel-albums-add-album-button-link" data-fancybox data-type="iframe">@lang('keywords.AddAlbum')</a>
            </div>
        </div>
        <div class="admin-panel-albums-pictures-and-albums-control-buttons">
            <div>    
                {!! Form::button(Lang::get('keywords.Edit'), 
                [ 'class' => 'admin-panel-albums-pictures-and-albums-control-button 
                admin-panel-albums-pictures-and-albums-control-button-disabled', 
                'id' => 'albums_button_edit', 'disabled' ]) !!}
            </div>
            <div>
                {!! Form::button(Lang::get('keywords.Delete'), 
                [ 'class' => 'admin-panel-albums-pictures-and-albums-control-button 
                admin-panel-albums-pictures-and-albums-control-button-disabled', 
                'id' => 'albums_button_delete', 'disabled' ]) !!}
            </div>           
        </div>
    </div>
    @if ($albums->count() > 0)
        <div class="admin-panel-albums-sorting">
            {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                           ['class' => 'admin-panel-albums-sorting-label']); !!}
            {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                              ['class' => 'admin-panel-albums-sorting-controls']); !!}       
        </div>
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">
                <div class="admin-panel-albums-picture-and-album-header-row">
                    <div class="admin-panel-albums-picture-and-album-header-field" id="albums_all_items_select_wrapper" 
                         title='{{ Lang::get("keywords.SelectAll") }}' 
                         data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                        {!! Form::checkbox('albums_all_items_select', 'value', false, ['id' => 'albums_all_items_select', 
                        'class' => 'admin-panel-albums-picture-and-album-header-checkbox']); !!}
                    </div>
                    <div class="admin-panel-albums-picture-and-album-header-field">
                        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
                            <div class="admin-panel-albums-picture-and-album-header-text">
                                <p>@lang('keywords.Name')</p>
                            </div>
                            <div class="admin-panel-albums-picture-and-album-header-caret">
                                @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_name" data-is_level_zero="1" data-sorting_mode="desc"
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_name" data-is_level_zero="1" data-sorting_mode="asc"
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-albums-picture-and-album-header-field">
                        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
                            <div class="admin-panel-albums-picture-and-album-header-text">
                                <p>@lang('keywords.DateAndTimeCreated')</p>
                            </div>
                            <div class="admin-panel-albums-picture-and-album-header-caret">
                                @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_creation" data-is_level_zero="1" data-sorting_mode="desc"
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_creation" data-is_level_zero="1" data-sorting_mode="asc" 
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-albums-picture-and-album-header-field">
                        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
                            <div class="admin-panel-albums-picture-and-album-header-text">
                                <p>@lang('keywords.DateAndTimeUpdate')</p>
                            </div>
                            <div class="admin-panel-albums-picture-and-album-header-caret">
                                @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                    <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_update" data-is_level_zero="1" data-sorting_mode="desc" 
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                @else
                                    <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-albums-picture-and-album-header-caret-used" : 
                                          "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                          id="sort_by_update" data-is_level_zero="1" data-sorting_mode="asc" 
                                          data-localization="{{ App::isLocale('en') ? 'en' : 'ru' }}" data-section="{{ $section }}"
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($albums as $album)
                    <div class="admin-panel-albums-picture-and-album-body-row">
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            {!! Form::checkbox('item_select', 1, false, 
                            ['data-keyword' => $album->keyword, 'data-parent_keyword' => $parent_keyword,
                             'data-entity_type' => 'directory',  'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                             'class' => 'admin-panel-albums-picture-and-album-checkbox']); !!}
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            <a href='{{ App::isLocale('en') ? "/admin/albums/".$album->keyword."/page/1" : 
                                        "/ru/admin/albums/".$album->keyword."/page/1" }}'>
                                <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">                            
                                    <img src="{{ ($album->is_visible==1) ? URL::asset('images/icons/album_folder.png') : 
                                                URL::asset('images/icons/album_folder_bnw.png') }}" 
                                                class="admin-panel-albums-albums-picture-image">                                                                   
                                    <div class="admin-panel-albums-picture-and-album-title">
                                        <p>{{ $album->album_name }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            <div class="admin-panel-albums-picture-and-album-body-field-content">
                                <p>{{ $album->created_at }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            <div class="admin-panel-albums-picture-and-album-body-field-content">
                                <p>{{ $album->updated_at }}</p>
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
