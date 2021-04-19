<!--The hidden field below I am doing with basic html, because Laravel Forms are not working properly, cannot pass value when using ajax.-->
<input type="hidden" name="search_is_on" id='search_is_on' value="1">
@if ($all_items_amount > 0)
    @include('pages.folders_and_articles.folders_searchdata')
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