@if ($all_items_amount > 0)
    <div class="admin-panel-articles-sorting">
        {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                       ['class' => 'admin-panel-articles-sorting-label']); !!}
        <!--The variable data-old_sorting_method_and_mode is required to keep previous 
            sorting options in case all elements are invisible and user wants to make them visible again.-->
        <!--data-parent_keyword is required only to update page correctly after deleting elements.-->
        {!! Form::checkbox('show_only_visible', $show_invisible, $show_invisible == 'all' ? false : true, 
                          ['id' => 'show_only_visible', 'class' => 'admin-panel-articles-sorting-controls', 
                           'data-localization' => (App::isLocale('en') ? 'en' : 'ru'),
                           'data-section' => $section, 'data-is_level_zero' => '1', 
                           'data-parent_keyword' => "0",
                           'data-old_sorting_method_and_mode' => $sorting_method_and_mode]); !!}       
    </div>
    @include('adminpages.folders.adminfolders_data')
    @if ($all_items_amount > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('search_paginator', ['pagination_info' => $pagination_info])
        @endcomponent
    @endif
@else
    <div class="admin-panel-articles-empty-folders-text-wrapper">
        <p>@lang('keywords.NothingFound')</p>
    </div>
@endif