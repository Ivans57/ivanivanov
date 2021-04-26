<div class="albums-and-pictures-sorting albums-and-pictures-sorting-for-included-albums">                        
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
</div>
<div class="albums-picture-wrapper">       
    @foreach ($albums_or_pictures as $album_or_picture)
        @if ($what_to_search === 'albums')
            <div class="album-item" title='{{ $album_or_picture->name }}'>
                <div class="album-body">                               
                    <a href={{ App::isLocale('en') ? "/albums/".$album_or_picture->keyword."/page/1" : 
                        "/ru/albums/".$album_or_picture->keyword."/page/1" }}>
                        <img src="{{ URL::asset('images/icons/album_folder.png') }}" alt="{{ $album_or_picture->name }}" class="album-folder">
                    </a>
                </div>
                <div class="album-title">
                    <h3 class="album-folder-title">{{ $album_or_picture->name }}</h3>
                </div>
            </div>
        @else
            <a href='{{ URL::asset($album_or_picture->path_to_file.$album_or_picture->file_name) }}'
                data-fancybox="group" data-caption="{{ $album_or_picture->name }}" title="{{ $album_or_picture->name }}">
                <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                    <div class="admin-panel-albums-pictures-picture">
                        <div><!-- These div is required to keep normal size of image without deforming it.-->
                            <img src="{{ URL::asset($album_or_picture->path_to_file.$album_or_picture->file_name) }}" 
                                 alt="{{ $album_or_picture->name }}"  
                                 class="admin-panel-albums-pictures-picture-image">
                        </div>
                    </div>
                    <div class="admin-panel-albums-picture-and-album-title">
                        <p>{{ $album_or_picture->name }}</p>
                    </div>
                </div>
            </a>
        @endif
    @endforeach       
</div>