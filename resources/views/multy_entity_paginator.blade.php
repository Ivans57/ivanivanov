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
        @if ($is_admin_panel == true)
            <!-- It is important to write absolute routes, otherwise there might be bugs when using the paginator. -->
            <a href='{{ App::isLocale('en') ? "/admin/".$section."/".$parent_keyword."/page/1/".$sorting_mode : 
                        "/ru/admin/".$section."/".$parent_keyword."/page/1/".$sorting_mode }}' 
                        class="first-active" title="@lang('keywords.ToFirstPage')"></a>
        @else
            <a href='{{ App::isLocale('en') ? "/".$section."/".$parent_keyword."/page/1/".$sorting_mode : 
                        "/ru/".$section."/".$parent_keyword."/page/1/".$sorting_mode }}' 
                        class="first-active" title="@lang('keywords.ToFirstPage')"></a>
        @endif
    @endif
    @if ($pagination_info->current_page == 1)
        <span class="previous-inactive"></span>
    @else
        @if ($is_admin_panel == true)
        <a href='{{ App::isLocale('en') ? "/admin/".$section."/".$parent_keyword."/page/".$pagination_info->previous_page."/".$sorting_mode : 
                    "/ru/admin/".$section."/".$parent_keyword."/page/".$pagination_info->previous_page."/".$sorting_mode }}' 
                    class="previous-active" title="@lang('keywords.ToPreviousPage')"></a>
        @else
            <a href='{{ App::isLocale('en') ? "/".$section."/".$parent_keyword."/page/".$pagination_info->previous_page."/".$sorting_mode : 
                    "/ru/".$section."/".$parent_keyword."/page/".$pagination_info->previous_page."/".$sorting_mode }}' 
                    class="previous-active" title="@lang('keywords.ToPreviousPage')"></a>
        @endif
    @endif
    <span class="pagination-info">{{ $pagination_info->current_page }} @lang('keywords.Of') {{ $pagination_info->number_of_pages }}</span>
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="next-inactive"></span>
    @else
        @if ($is_admin_panel == true)
            <a href='{{ App::isLocale('en') ? "/admin/".$section."/".$parent_keyword."/page/".$pagination_info->next_page."/".$sorting_mode : 
                        "/ru/admin/".$section."/".$parent_keyword."/page/".$pagination_info->next_page."/".$sorting_mode }}' 
                        class="next-active" title="@lang('keywords.ToNextPage')"></a>
        @else
            <a href='{{ App::isLocale('en') ? "/".$section."/".$parent_keyword."/page/".$pagination_info->next_page."/".$sorting_mode : 
                        "/ru/".$section."/".$parent_keyword."/page/".$pagination_info->next_page."/".$sorting_mode }}' 
                        class="next-active" title="@lang('keywords.ToNextPage')"></a>
        @endif
    @endif
    @if ($pagination_info->current_page == $pagination_info->number_of_pages)
        <span class="last-inactive"></span>
    @else
        @if ($is_admin_panel == true)
            <a href='{{ App::isLocale('en') ? "/admin/".$section."/".$parent_keyword."/page/".$pagination_info->number_of_pages."/".$sorting_mode : 
                        "/ru/admin/".$section."/".$parent_keyword."/page/".$pagination_info->number_of_pages."/".$sorting_mode }}' 
                        class="last-active" title="@lang('keywords.ToLastPage')"></a>
        @else
            <a href='{{ App::isLocale('en') ? "/".$section."/".$parent_keyword."/page/".$pagination_info->number_of_pages."/".$sorting_mode : 
                        "/ru/".$section."/".$parent_keyword."/page/".$pagination_info->number_of_pages."/".$sorting_mode }}' 
                        class="last-active" title="@lang('keywords.ToLastPage')"></a>
        @endif
    @endif
</div>