{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
<!-- Two conditions below are required to display visibility checkbox properly. -->
@if ($all_albums_count > 0)
    <div class="admin-panel-albums-sorting">
        {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                       ['class' => 'admin-panel-albums-sorting-label']); !!}
        <!--The variable data-old_sorting_method_and_mode is required to keep previous 
            sorting options in case all elements are invisible and user wants to make them visible again.-->
        <!--data-parent_keyword is required only to update page correctly after deleting elements.-->
        {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                          ['id' => 'show_only_visible', 'class' => 'admin-panel-albums-sorting-controls', 
                           'data-localization' => (App::isLocale('en') ? 'en' : 'ru'),
                           'data-section' => $section, 'data-is_level_zero' => '1', 'data-parent_keyword' => "0",
                           'data-old_sorting_method_and_mode' => $sorting_method_and_mode]); !!}       
    </div>
@endif
@if ($albums->count() > 0)
    @include('adminpages.albums.adminalbums_data')    
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