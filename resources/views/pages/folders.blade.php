@extends('app')

@section('content')
<article class="website-main-article articles-article-folders">
    @if ($folders->count() > 0)
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