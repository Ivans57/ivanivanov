<div class="paginator">
                @if ($pagination_info->current_page == 1)
                    <span class="first-inactive"></span>
                @else
                    <a href="1" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                @endif
                @if ($pagination_info->current_page == 1)
                    <span class="previous-inactive"></span>
                @else
                    <a href="{{ $pagination_info->previous_page }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                @endif
                <span class="pagination-info">{{ $pagination_info->current_page }} @lang('pagination.Of') {{ $pagination_info->number_of_pages }}</span>
                @if ($pagination_info->current_page == $pagination_info->number_of_pages)
                    <span class="next-inactive"></span>
                @else
                    <a href="{{ $pagination_info->next_page }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                @endif
                @if ($pagination_info->current_page == $pagination_info->number_of_pages)
                    <span class="last-inactive"></span>
                @else
                    <a href="{{ $pagination_info->number_of_pages }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                @endif
</div>