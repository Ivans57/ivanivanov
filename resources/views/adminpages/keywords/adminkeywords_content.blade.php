@if ($keywords->count() > 0)
    @include('adminpages.keywords.adminkeywords_data')
    @if ($keywords->total() > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('one_entity_paginator', ['items' => $keywords])
        @endcomponent
    @endif
@else
    <div class="admin-panel-keywords-empty-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif