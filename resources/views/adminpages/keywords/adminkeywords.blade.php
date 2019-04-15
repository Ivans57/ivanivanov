@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article admin-panel-main-article-keywords">
    <div class="admin-panel-keywords-add-keyword-button">
        <a href={{ App::isLocale('en') ? "/admin/keywords/create" : "/ru/admin/keywords/create" }} 
        class="admin-panel-keywords-add-keyword-button-link" data-fancybox data-type="iframe">
            @lang('keywords.AddKeyword')
        </a>   
    </div>
    @if ($keywords->count() > 0)
        <!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
        in case we don't have full page-->
        <div class="admin-panel-keywords-external-keywords-wrapper">
            <div class="admin-panel-keywords-keywords-wrapper">
                <div class="admin-panel-keywords-keywords-header-wrapper">
                    <div class="admin-panel-keywords-keywords-header">
                        <h3>@lang('keywords.Keyword')</h3>
                    </div>
                    <div class="admin-panel-keywords-keywords-header">
                        <h3>@lang('keywords.Text')</h3>
                    </div>
                    <div class="admin-panel-keywords-keywords-header"></div>
                </div>
                @foreach ($keywords as $keyword)
                    <div class="admin-panel-keywords-keyword-wrapper">    
                        <div class="admin-panel-keywords-keyword">
                            <p>{{ $keyword->keyword }}</p>
                        </div>
                        <div class="admin-panel-keywords-keyword">
                            <p>{{ $keyword->text }}</p>
                        </div>
                        <div class="admin-panel-keywords-keyword admin-panel-keywords-keyword-control-buttons-wrapper">
                            <div class="admin-panel-keywords-keyword-control-buttons">
                                <div class="admin-panel-keywords-keyword-control-button">
                                    <a href={{ App::isLocale('en') ? "keywords/".$keyword->keyword."/edit" : "/ru/admin/keywords/".$keyword->keyword."/edit" }} class="admin-panel-keywords-keyword-control-button-link">@lang('keywords.Edit')</a>
                                </div>
                                <div class="admin-panel-keywords-keyword-control-button">
                                    <a href='#' class="admin-panel-keywords-keyword-control-button-link">@lang('keywords.Delete')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>        
        </div>
        @if ($keywords->total() > $items_amount_per_page)
        <div class="admin-panel-paginator">
            @if ($keywords->currentPage() == 1)
                <span class="first-inactive"></span>
            @else
                <a href="{{ $keywords->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
            @endif
            @if ($keywords->currentPage() == 1)
                <span class="previous-inactive"></span>
            @else
                <a href="{{ $keywords->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
            @endif
                <span class="pagination-info">{{ $keywords->currentPage() }} @lang('pagination.Of') {{ $keywords->lastPage() }}</span>
            @if ($keywords->currentPage() == $keywords->lastPage())
                <span class="next-inactive"></span>
            @else
                <a href="{{ $keywords->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
            @endif
            @if ($keywords->currentPage() == $keywords->lastPage())
                <span class="last-inactive"></span>
            @else
                <a href="{{ $keywords->url($keywords->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
            @endif
        </div>
        @endif
    @else
        <div class="admin-panel-keywords-empty-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif
</article>

@stop


