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
        @include('adminpages.albums.adminalbum_data')
        @if ($total_number_of_items > $items_amount_per_page)
            <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
            @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                       'parent_keyword' => $parent_keyword, 'is_admin_panel' => $is_admin_panel,
                       'show_invisible' => $show_invisible, 'sorting_mode' => $sorting_mode, 
                       'directories_or_files_first' => $albums_or_pictures_first])
            @endcomponent
        @endif
    @else
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif