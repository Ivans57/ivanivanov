@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-albums">  
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
    <div class="admin-panel-albums-control-buttons">
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
    @if ($allPicturesAmount > 0 || $allAlbumsAmount > 0)
        <div class="admin-panel-albums-sorting">
            {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                           ['class' => 'admin-panel-albums-sorting-label']); !!}
            {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                              ['id' => 'show_only_visible', 'class' => 'admin-panel-albums-sorting-controls', 
                              'data-localization' => (App::isLocale('en') ? 'en' : 'ru'),
                              'data-section' => $section, 'data-is_level_zero' => '0', 'data-parent_keyword' => $parent_keyword,
                              'data-old_sorting_method_and_mode' => $sorting_method_and_mode,
                              'data-old_directories_or_files_first' => $albums_or_pictures_first]); !!}                              
            @if ($pictureAmount > 0 && $albumAmount > 0)                   
                {!! Form::label('folders_first', Lang::get('keywords.AlbumsFirst').':', 
                               ['class' => 'admin-panel-albums-sorting-label']) !!}               
                {!! Form::radio('directories_or_files_first', 'albums_first', 
                               (($albums_or_pictures_first === 'albums_first') ? true : false), ['id' => 'albums_first', 
                                'class' => 'admin-panel-albums-sorting-controls']); !!}                    
                {!! Form::label('articles_first', Lang::get('keywords.PicturesFirst').':', 
                               ['class' => 'admin-panel-albums-sorting-label']) !!}               
                {!! Form::radio('directories_or_files_first', 'pictures_first', 
                               (($albums_or_pictures_first === 'pictures_first') ? true : false), ['id' => 'pictures_first', 
                                'class' => 'admin-panel-albums-sorting-controls']); !!}                    
            @endif     
        </div>
    @endif
    @if ($total_number_of_items > 0)
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
                            @if (($pictureAmount > 1 || $albumAmount > 1))
                                <div class="admin-panel-albums-picture-and-album-header-caret">
                                    @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                        <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                            "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_name" data-is_level_zero="0" data-sorting_mode="desc" 
                                            title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                                    @else
                                        <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                            "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_name" data-is_level_zero="0" data-sorting_mode="asc" 
                                            title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="admin-panel-albums-picture-and-album-header-field">
                        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
                            <div class="admin-panel-albums-picture-and-album-header-text">
                                <p>@lang('keywords.DateAndTimeCreated')</p>
                            </div>
                            @if (($pictureAmount > 1 || $albumAmount > 1))
                                <div class="admin-panel-albums-picture-and-album-header-caret">
                                    @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                        <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                            "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_creation" data-is_level_zero="0" data-sorting_mode="desc" 
                                            title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                                    @else
                                        <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                            "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_creation" data-is_level_zero="0" data-sorting_mode="asc" 
                                            title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="admin-panel-albums-picture-and-album-header-field">
                        <div class="admin-panel-albums-picture-and-album-header-text-and-caret-wrapper">
                            <div class="admin-panel-albums-picture-and-album-header-text">
                                <p>@lang('keywords.DateAndTimeUpdate')</p>
                            </div>
                            @if (($pictureAmount > 1 || $albumAmount > 1))
                                <div class="admin-panel-albums-picture-and-album-header-caret">
                                    @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                        <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                              "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_update" data-is_level_zero="0" data-sorting_mode="desc" 
                                            title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                    @else
                                        <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                            "admin-panel-albums-picture-and-album-header-caret-used" : 
                                            "admin-panel-albums-picture-and-album-header-caret-unused" }}'
                                            id="sort_by_update" data-is_level_zero="0" data-sorting_mode="asc" 
                                            title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @foreach ($albums_and_pictures as $album_or_picture)   
                    <div class="admin-panel-albums-picture-and-album-body-row">
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            @if ($album_or_picture->type == 'album')
                                {!! Form::checkbox('item_select', 1, false, 
                                ['data-keyword' => $album_or_picture->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                 'data-entity_type' => 'directory', 'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                 'class' => 'admin-panel-albums-picture-and-album-checkbox' ]); !!}
                            @else
                                {!! Form::checkbox('item_select', 1, false, 
                                ['data-keyword' => $album_or_picture->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                 'data-entity_type' => 'file',  'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                 'class' => 'admin-panel-albums-picture-and-album-checkbox' ]) !!}
                            @endif
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            @if ($album_or_picture->type == 'album')
                                <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/page/1" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/page/1" }}>
                                    <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                                        <img src="{{ ($album_or_picture->isVisible==1) ? 
                                            URL::asset('images/icons/album_folder.png') : 
                                                    URL::asset('images/icons/album_folder_bnw.png') }}" 
                                                    class="admin-panel-albums-albums-picture-image">                  
                                        <div class="admin-panel-albums-picture-and-album-title">
                                            <p>{{ $album_or_picture->caption }}</p>
                                        </div>
                                    </div>
                                </a>
                            @elseif ($album_or_picture->type == 'picture')
                                <a href='{{ URL::asset($pathToFile.$album_or_picture->fileName) }}'
                                        data-fancybox="group" data-caption="{{ $album_or_picture->caption }}" title="{{ $album_or_picture->caption }}">
                                    <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                                        <div class="admin-panel-albums-pictures-picture">
                                            <div><!-- These div is required to keep normal size of image without deforming it.-->
                                                <img src="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" 
                                                     alt="{{ $album_or_picture->caption }}" 
                                                     style="{{ ($album_or_picture->isVisible==1) ? 'opacity:1' : 'opacity:0.45' }}" 
                                                     class="admin-panel-albums-pictures-picture-image">
                                            </div>
                                        </div>
                                        <div class="admin-panel-albums-picture-and-album-title">
                                            <p>{{ $album_or_picture->caption }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            <div class="admin-panel-albums-picture-and-album-body-field-content">
                                <p>{{ $album_or_picture->createdAt }}</p>    
                            </div>    
                        </div>
                        <div class="admin-panel-albums-picture-and-album-body-field">
                            <div class="admin-panel-albums-picture-and-album-body-field-content">
                                <p>{{ $album_or_picture->updatedAt }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach     
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                       'parent_keyword' => $parent_keyword, 'sorting_mode' => $sorting_mode, 'is_admin_panel' => $is_admin_panel])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
