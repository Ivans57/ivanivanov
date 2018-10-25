@extends('admin')

@section('admincontent')
<div class="admin-panel-main-path-wrapper">
    <div class="admin-panel-main-path">
        <p>This is main path panel.</p>
    </div>
</div>
<!-- "admin-panel-main-article-wrapper" we might not need if we remove the path section above -->
<div class="admin-panel-main-article-wrapper">
    <article class="admin-panel-main-article">
        <div class="admin-panel-add-article-folder-wrapper">
            {!! Form::button(Lang::get('keywords.AddArticle')) !!}
            {!! Form::button(Lang::get('keywords.AddFolder')) !!}

        </div>         
        @if ($folders->count() > 0)
            <div class="admin-panel-external-article-folders-wrapper">
                <div class="admin-panel-article-folders-wrapper">
                    @foreach ($folders as $folder)
                        <div class="admin-panel-folder-item">
                            <div class="admin-panel-folder-title">
                                <p>{{ $folder->folder_name }}</p>
                            </div>
                            <div class="admin-panel-folder-buttons-wrapper">
                                <div class="admin-panel-folder-buttons">
                                    {!! Form::button(Lang::get('keywords.Open')) !!}
                                    {!! Form::button(Lang::get('keywords.Edit')) !!}
                                    {!! Form::button(Lang::get('keywords.Delete')) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="admin-panel-empty-folders-text-wrapper">
                <p>@lang('keywords.EmptySection')</p>
            </div>
        @endif  
        @if ($folders->total() > 25)
            <div class="admin-panel-paginator">
                @if ($folders->currentPage() == 1)
                    <span class="first-inactive"></span>
                @else
                    <a href="{{ $folders->url(1) }}" class="first-active" title="@lang('pagination.ToFirstPage')"></a>
                @endif
                @if ($folders->currentPage() == 1)
                    <span class="previous-inactive"></span>
                @else
                    <a href="{{ $folders->previousPageUrl() }}" class="previous-active" title="@lang('pagination.ToPreviousPage')"></a>
                @endif
                <span class="pagination-info">{{ $folders->currentPage() }} @lang('pagination.Of') {{ $folders->lastPage() }}</span>
                @if ($folders->currentPage() == $folders->lastPage())
                    <span class="next-inactive"></span>
                @else
                    <a href="{{ $folders->nextPageUrl() }}" class="next-active" title="@lang('pagination.ToNextPage')"></a>
                @endif
                @if ($folders->currentPage() == $folders->lastPage())
                    <span class="last-inactive"></span>
                @else
                    <a href="{{ $folders->url($folders->lastPage()) }}" class="last-active" title="@lang('pagination.ToLastPage')"></a>
                @endif
            </div>
        @endif
    </article>
</div>
@stop
