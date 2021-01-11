@if ($all_keywords_amount > 0)
    @include('adminpages.adminkeywords_data')
    @if ($all_keywords_amount > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('search_paginator', ['pagination_info' => $pagination_info])
        @endcomponent
    @endif
@else
    <div class="admin-panel-keywords-empty-text-wrapper">
        <p>@lang('keywords.NothingFound')</p>
    </div>
@endif