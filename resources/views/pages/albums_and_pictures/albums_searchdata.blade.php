<div class="albums-and-pictures-sorting albums-and-pictures-sorting-for-included-albums">
    @if ($all_items_amount > 1)
        {!! Form::label('sort', Lang::get('keywords.Sorting').':', 
                       ['class' => 'albums-and-pictures-sorting-label']) !!}           
        {!! Form::select('sort', array(
                         'sort_by_creation_desc' => Lang::get('keywords.LatestFirst'), 
                         'sort_by_creation_asc' => Lang::get('keywords.OldestFirst'), 
                         'sort_by_name_desc' => Lang::get('keywords.SortByNameDescending'), 
                         'sort_by_name_asc' => Lang::get('keywords.SortByNameAscending')), 
                          $sorting_method_and_mode, ['id' => 'sort', 
                         'class' => 'form-control albums-and-pictures-sorting-controls albums-and-pictures-sorting-select', 
                         'data-section' => $section, 'data-is_level_zero' => '0', 
                         'data-localization' => App::isLocale('en') ? 'en' : 'ru']) !!}
    @endif
</div>
<div class="albums-picture-wrapper">       
    @foreach ($albums_or_pictures as $album_or_picture)
        <div class="albums-album-or-picture">
            @if ($what_to_search === 'albums')
                <a href={{ App::isLocale('en') ? "/albums/".$album_or_picture->keyword."/page/1" : "/ru/albums/".$album_or_picture->keyword."/page/1" }} 
                    class="albums-album-link">
                    <div class="albums-album-picture">
                        <img src="{{ URL::asset('images/icons/album_folder.png')}}" alt="{{ $album_or_picture->name }}" class="albums-album-picture-image">
                    </div>
                    <div class="albums-album-title albums-album-title-for-album-folder"><p>{{ $album_or_picture->name }}</p></div>
                </a>
            @else
                <a href='{{ URL::asset($album_or_picture->path_to_file.$album_or_picture->file_name) }}' data-fancybox="group" 
                   data-caption="{{ $album_or_picture->name }}" title="{{ $album_or_picture->name }}" class="albums-album-link">
                        <div class="albums-album-picture">
                                <img src="{{ URL::asset($album_or_picture->path_to_file.$album_or_picture->file_name) }}" alt="{{ $album_or_picture->name }}" 
                                     class="albums-album-picture-image">
                        </div>    
                    <div class="albums-album-title"><p>{{ $album_or_picture->name }}</p></div>
                </a>
            @endif
        </div>
    @endforeach       
</div>