<!-- For search special paginator is required, because for search is used POST type method.-->
<div class="paginator">
    @if ($pagination_info->current_page == 1)
        <span class="first-inactive"></span>
    @else
        <span class="first-active" title="@lang('keywords.ToFirstPage')"></span>
    @endif
    @if ($pagination_info->current_page == 1)
        <span class="previous-inactive"></span>
    @else
        <span class="previous-active" title="@lang('keywords.ToPreviousPage')"></span>
    @endif
    <span class="pagination-info">{{ $pagination_info->current_page }} @lang('keywords.Of') {{ $pagination_info->number_of_pages }}</span>
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="next-inactive"></span>
    @else
        <span class="next-active" title="@lang('keywords.ToNextPage')"></span>
    @endif
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="last-inactive"></span>
    @else
        <span class="last-active" title="@lang('keywords.ToLastPage')"></span>
    @endif
</div>