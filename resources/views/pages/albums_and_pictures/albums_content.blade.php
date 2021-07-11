{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
@if ($album_links->count() > 0)
    @if ($album_links->count() > 1)
        <div class="albums-and-pictures-sorting">
            {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                           ['class' => 'albums-and-pictures-sorting-label']) !!}
            {!! Form::select('sort', array(
                             'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                             'sort_by_name_desc' => Lang::get('keywords.SortByNameDescending'), 'sort_by_name_asc' => Lang::get('keywords.SortByNameAscending')), 
                              $sorting_mode, ['id' => 'sort', 'class' => 'form-control albums-and-pictures-sorting-select', 
                             'data-section' => $section, 'data-is_level_zero' => '1', 
                             'data-localization' => App::isLocale('en') ? 'en' : 'ru']) !!}
        </div>
    @endif
    <div class="external-albums-wrapper">
        <div class="albums-wrapper">
            @foreach ($album_links as $album_link)
                <div class="album-item" title='{{ $album_link->album_name }}'>
                    <div class="album-body">
                        <a href='{{ App::isLocale("en") ? "/albums/".$album_link->keyword."/page/1" : "/ru/albums/".$album_link->keyword."/page/1" }}'>
                            <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_link->album_name }}" class="album-folder">
                        </a>
                    </div>
                    <div class="album-title">
                        <h3 class="album-folder-title">{{ $album_link->album_name }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if ($album_links->total() > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('one_entity_paginator', ['items' => $album_links])
        @endcomponent
    @endif
@else
    <div class="empty-albums-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif