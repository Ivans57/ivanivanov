@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    @if ($folders->count() > 0)
        <div class="folders-and-articles-sorting">
            {!! Form::label('sort', 'Sorting:', 
            ['class' => 'folders-and-articles-sorting-label']) !!}
            {!! Form::select('sort', array('sort_by_update_desc' => 'Latest first', 'sort_by_update_asc' => 'Oldest first', 
                             'sort_by_name_desc' => 'Sort by name descending', 'sort_by_name_asc' => 'Sort by name ascending'), 
                             'sort_by_update_desc', ['id' => 'sort', 'class' => 'form-control folders-and-articles-sorting-select', 
                             'data-section' => $section, 'data-is_level_zero' => '1', 
                             'data-localization' => App::isLocale('en') ? 'en' : 'ru']) !!}
        </div>
        <div class="external-folders-wrapper">
            <div class="folders-wrapper">
                @foreach ($folders as $folder)
                    <div class="folder-item">
                        <div class="folder-body">
                            <a href='articles/{{ $folder->keyword }}/page/1'>
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
</article>
@stop