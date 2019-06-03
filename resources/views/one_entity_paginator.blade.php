<!-- There are two types of paginators in this project to simplify the code.
In the case with one entity I just pass to the view the full array of items I took 
from the query and I can make pagination with embedded Laravel methods. It is impossible
to do the same with multiple entities. At the same time I can not apply paginator
for multiple entity items for one entity arrays. To do this I need to do some 
operations with an array of items from query and will add more lines of useless 
code in repository.-->
<div class="paginator">
    @if ($items->currentPage() == 1)
        <span class="first-inactive"></span>
    @else
        <a href="{{ $items->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
    @endif
    @if ($items->currentPage() == 1)
        <span class="previous-inactive"></span>
    @else
        <a href="{{ $items->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
    @endif
    <span class="pagination-info">{{ $items->currentPage() }} @lang('pagination.Of') {{ $items->lastPage() }}</span>
    @if ($items->currentPage() == $items->lastPage())
        <span class="next-inactive"></span>
    @else
        <a href="{{ $items->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
    @endif
    @if ($items->currentPage() == $items->lastPage())
        <span class="last-inactive"></span>
    @else
        <a href="{{ $items->url($items->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
    @endif
</div>