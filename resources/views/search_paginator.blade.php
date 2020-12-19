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
        <span class="first-active" title="@lang('keywords.ToFirstPage')"></span>
    @endif
    @if ($items->currentPage() == 1)
        <span class="previous-inactive"></span>
    @else
        <span class="previous-active" title="@lang('keywords.ToPreviousPage')"></span>
    @endif
    <span class="pagination-info">{{ $items->currentPage() }} @lang('keywords.Of') {{ $items->lastPage() }}</span>
    @if ($items->currentPage() == $items->lastPage())
        <span class="next-inactive"></span>
    @else
        <span class="next-active" title="@lang('keywords.ToNextPage')"></span>
    @endif
    @if ($items->currentPage() == $items->lastPage())
        <span class="last-inactive"></span>
    @else
        <span class="last-active" title="@lang('keywords.ToLastPage')"></span>
    @endif
</div>