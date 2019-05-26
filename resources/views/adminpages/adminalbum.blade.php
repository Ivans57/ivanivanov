@extends('admin')

@section('admincontent')

<article class="admin-panel-main-article">  
    <div class="path-panel">
        <span class="path-panel-text">@lang('keywords.Path'):</span>
        <a href={{ App::isLocale('en') ? "/admin/albums" : "/ru/admin/albums" }} class="path-panel-text">@lang('keywords.Albums')</a>
        <span class="path-panel-text"> /</span>
        @if ($albumParents > 0)
            @foreach ($albumParents as $albumParent)
                <a href={{ App::isLocale('en') ? "/admin/albums/".$albumParent->keyWord."/page/1" : 
                   "/ru/admin/albums/".$albumParent->keyWord."/page/1" }} class="path-panel-text">{{ $albumParent->albumName }}</a>
                <span class="path-panel-text"> /</span>
            @endforeach
        @endif
    </div> 
    <div>
        <h2>{{ $headTitle }}</h2>
    </div>    
    <div class="admin-panel-albums-add-picture-album-wrapper">
        <div class="admin-panel-albums-add-picture-album-button">
            <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddPicture')</a>
        </div>
        <div class="admin-panel-albums-add-picture-album-button">
            <a href='#' class="admin-panel-albums-add-picture-album-button-link">@lang('keywords.AddAlbum')</a>
        </div>
    </div>
    @if ($total_number_of_items > 0)
        <div class="admin-panel-albums-external-pictures-and-albums-wrapper">
            <div class="admin-panel-albums-pictures-and-albums-wrapper">          
                @foreach ($albums_and_pictures as $album_or_picture)   
                    <div class="admin-panel-albums-picture-and-album-item">
                        <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                            <div>
                                <img src="{{ $album_or_picture->type == 'album' ? URL::asset('images/icons/regular_folder_small.png') : URL::asset('images/icons/article.png') }}">
                            </div>
                            <div class="admin-panel-albums-picture-and-album-title">
                                <p>{{ $album_or_picture->caption }}</p>
                            </div>
                        </div>
                        <div class="admin-panel-albums-picture-and-album-control-buttons-wrapper">
                            <div class="admin-panel-albums-picture-and-album-control-buttons">
                                @if ($album_or_picture->type == 'album')
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href={{ App::isLocale('en') ? "/admin/albums/".$album_or_picture->keyWord."/page/1" : 
                                            "/ru/admin/albums/".$album_or_picture->keyWord."/page/1" }} 
                                            class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                @elseif ($album_or_picture->type == 'picture') 
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Open')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Edit')</a>
                                    </div>
                                    <div class="admin-panel-albums-picture-and-album-control-button">
                                        <a href='#' class="admin-panel-albums-picture-and-album-control-button-link">@lang('keywords.Delete')</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach     
            </div>
        </div>
        @if ($total_number_of_items > $items_amount_per_page)
            <div class="admin-panel-paginator">
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
        @endif
    @else
        <div class="admin-panel-albums-empty-albums-text-wrapper">
            <p>@lang('keywords.EmptySection')</p>
        </div>
    @endif   
</article>

@stop
