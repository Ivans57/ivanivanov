<!-- There are two types of paginators in this project to simplify the code.
In the case with one entity I just pass to the view the full array of items I took 
from the query and I can make pagination with embedded Laravel methods. It is impossible
to do the same with multiple entities. At the same time I can not apply paginator
for multiple entity items for one entity arrays. To do this I need to do some 
operations with an array of items from query and will add more lines of useless 
code in repository.-->
<div class="paginator">
    @if ($pagination_info->current_page == 1)
        <span class="first-inactive"></span>
    @else
        <a href="1" class="first-active" title="@lang('keywords.ToFirstPage')"></a>
    @endif
    @if ($pagination_info->current_page == 1)
        <span class="previous-inactive"></span>
    @else
        <a href="{{ $pagination_info->previous_page }}" class="previous-active" title="@lang('keywords.ToPreviousPage')"></a>
    @endif
    <span class="pagination-info">{{ $pagination_info->current_page }} @lang('keywords.Of') {{ $pagination_info->number_of_pages }}</span>
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="next-inactive"></span>
    @else
        <a href="{{ $pagination_info->next_page }}" class="next-active" title="@lang('keywords.ToNextPage')"></a>
    @endif
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="last-inactive"></span>
    @else
        <a href="{{ $pagination_info->number_of_pages }}" class="last-active" title="@lang('keywords.ToLastPage')"></a>
    @endif
</div>