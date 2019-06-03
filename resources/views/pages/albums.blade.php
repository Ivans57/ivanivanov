@extends('app')

@section('content')
<article class="website-main-article albums-article">
    @if ($album_links->count() > 0)
        <div class="external-albums-wrapper">
            <div class="albums-wrapper">
                @foreach ($album_links as $album_link)
                    <div class="album-item">
                        <div class="album-body">
                            <a href='albums/{{ $album_link->keyword }}/page/1'>
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
            <p>@lang('folderContent.EmptySection')</p>
        </div>
    @endif
</article>
@stop