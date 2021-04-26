{!! Form::hidden('search_is_on', '0', ['id' => 'search_is_on']); !!}
@if ($total_number_of_items > 0)
    <div class="albums-and-pictures-sorting albums-and-pictures-sorting-for-included-albums">                        
        {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                       ['class' => 'albums-and-pictures-sorting-label']) !!}           
        {!! Form::select('sort', array(
                         'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 
                         'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                         'sort_by_name_desc' => Lang::get('keywords.SortByNameDescending'), 
                         'sort_by_name_asc' => Lang::get('keywords.SortByNameAscending')), 
                          $sorting_mode, ['id' => 'sort', 
                         'class' => 'form-control albums-and-pictures-sorting-controls albums-and-pictures-sorting-select', 
                         'data-section' => $section, 'data-parent_keyword' => $parent_keyword, 'data-is_level_zero' => '0', 
                         'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                         'data-has_files' => ($pictureAmount > 0) ? 'true' : 'false', 
                         'data-has_directories' => ($albumAmount > 0) ? 'true' : 'false']) !!}           
        @if ($pictureAmount > 0 && $albumAmount > 0)                   
            {!! Form::label('albums_first', Lang::get('keywords.AlbumsFirst').':', ['class' => 'albums-and-pictures-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'albums_first', 
                           (($directories_or_files_first === 'albums_first') ? true : false), ['id' => 'albums_first', 
                            'class' => 'albums-and-pictures-sorting-controls']); !!}                    
            {!! Form::label('pictures_first', Lang::get('keywords.PicturesFirst').':', ['class' => 'albums-and-pictures-sorting-label']) !!}               
            {!! Form::radio('directories_or_files_first', 'pictures_first', 
                           (($directories_or_files_first === 'pictures_first') ? true : false), ['id' => 'pictures_first', 
                            'class' => 'albums-and-pictures-sorting-controls']); !!}                    
        @endif          
    </div>
    <div class="external-albums-picture-wrapper">
        <div class="albums-picture-wrapper">       
            @foreach ($albums_and_pictures as $album_or_picture)
                @if ($album_or_picture->type == 'album')
                    <div class="album-item" title='{{ $album_or_picture->caption }}'>
                        <div class="album-body">                               
                            <a href={{ App::isLocale('en') ? "/albums/".$album_or_picture->keyWord."/page/1" : 
                                "/ru/albums/".$album_or_picture->keyWord."/page/1" }}>
                                <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->caption }}" class="album-folder">
                            </a>
                        </div>
                        <div class="album-title">
                            <h3 class="album-folder-title">{{ $album_or_picture->caption }}</h3>
                        </div>
                    </div>
                @else
                    <div class="album-picture">
                        <a class="album-picture-link" 
                            href="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" 
                            data-fancybox="group" data-caption="{{ $album_or_picture->caption }}" title="{{ $album_or_picture->caption }}">
                            <img src="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" alt="{{ $album_or_picture->caption }}" class="album-picture-link-picture">
                        </a>
                    </div>
                @endif
            @endforeach       
        </div>
    </div>
    @if ($total_number_of_items > $items_amount_per_page)
        <!--As it is impossible to pass an object via slot, we will pass it via attributes-->
        @component('multy_entity_paginator', ['pagination_info' => $pagination_info, 'section' => $section, 
                   'parent_keyword' => $parent_keyword, 'is_admin_panel' => $is_admin_panel, 
                   'sorting_mode' => $sorting_mode, 'directories_or_files_first' => $directories_or_files_first])
        @endcomponent
    @endif
@else
    <div class="empty-albums-text-wrapper">
        <p>@lang('keywords.EmptyAlbum')</p>
    </div>
@endif