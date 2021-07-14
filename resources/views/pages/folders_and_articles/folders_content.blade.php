{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
@if ($folders->count() > 0)
    @if ($folders->count() > 1)
        <div class="folders-and-articles-sorting">
            {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                ['class' => 'folders-and-articles-sorting-label']) !!}
            {!! Form::select('sort', array(
                             'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                             'sort_by_name_desc' => Lang::get('keywords.SortByNameDesc'), 'sort_by_name_asc' => Lang::get('keywords.SortByNameAsc')), 
                             $sorting_mode, ['id' => 'sort', 'class' => 'folders-and-articles-sorting-select', 
                             'data-section' => $section, 'data-is_level_zero' => '1', 
                             'data-localization' => App::isLocale('en') ? 'en' : 'ru']) !!}
        </div>
    @endif
    <div class="external-folders-wrapper">
        <div class="folders-wrapper">
            @foreach ($folders as $folder)
                <div class="folder-item" title='{{ $folder->folder_name }}'>
                    <div class="folder-body">
                        <a href='{{ App::isLocale("en") ? "/articles/".$folder->keyword."/page/1" : "/ru/articles/".$folder->keyword."/page/1" }}'>
                            <img src="{{ URL::asset('images/icons/regular_folder.png') }}" alt="{{ $folder->folder_name }}" class="article-folder">
                        </a>
                    </div>
                    <div class="folder-title">
                        <h3 class="article-folder-title">{{ $folder->folder_name }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if ($folders->total() > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('one_entity_paginator', ['items' => $folders])
        @endcomponent
    @endif
@else
    <div class="empty-folders-text-wrapper">
        <p>@lang('keywords.EmptySection')</p>
    </div>
@endif