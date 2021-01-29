<!--The hidden field below I am doing with basic html, because Laravel Forms are not working properly, cannot pass value when using ajax.-->
<input type="hidden" name="search_is_on" id='search_is_on' value="1">
@if ($all_items_amount_including_invisible > 0)
    <div class="admin-panel-articles-sorting">
        {!! Form::label('show_only_visible', Lang::get('keywords.ShowOnlyVisible').':', 
                       ['class' => 'admin-panel-articles-sorting-label']); !!}
        <!--The variable data-old_sorting_method_and_mode is required to keep previous 
            sorting options in case all elements are invisible and user wants to make them visible again.-->
        <!--data-parent_keyword is required only to update page correctly after deleting elements.-->
        <!--The checkbox below I am doing with basic html, because Laravel Forms are not working properly, cannot pass checked value when using ajax.-->
        <input type="checkbox" name="show_only_visible" id="show_only_visible" value={{ $show_invisible }} {{ $show_invisible == 'all' ? '' : 'checked="checked"'}}
               class = 'admin-panel-articles-sorting-controls' data-localization = {{ (App::isLocale('en') ? 'en' : 'ru') }} data-section = {{ $section }}
               data-is_level_zero = '1' data-parent_keyword = "0" data-old_sorting_method_and_mode = {{ $sorting_method_and_mode }}>
    </div>
@endif
@if ($all_items_amount > 0)
    @include('adminpages.folders.adminfolders_searchdata')
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