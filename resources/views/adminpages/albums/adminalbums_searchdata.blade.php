<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
in case we don't have full page-->
<div class="admin-panel-albums-external-pictures-and-albums-wrapper">
    <div class="admin-panel-albums-pictures-and-albums-wrapper">
        @include('adminpages.albums.adminalbums_data_header')
        @foreach ($albums_or_pictures as $album_or_picture)
            <div class="admin-panel-albums-picture-and-album-body-row">
                <div class="admin-panel-albums-picture-and-album-body-field">
                    {!! Form::checkbox('item_select', 1, false, 
                                      ['data-keyword' => $album->keyword, 'data-parent_keyword' => $parent_keyword,
                                       'data-entity_type' => ($what_to_search === 'folders') ? 'directory' : 'file', 
                                       'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                       'class' => 'admin-panel-albums-picture-and-album-checkbox']); !!}
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field admin-panel-albums-picture-and-album-body-field-name">
                    @if ($what_to_search === 'albums')
                        <a href='{{ App::isLocale('en') ? "/admin/albums/".$album->keyword."/page/1" : 
                                "/ru/admin/albums/".$album->keyword."/page/1" }}'>
                            <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">                            
                                <img src="{{ ($album->is_visible==1) ? URL::asset('images/icons/album_folder.png') : 
                                              URL::asset('images/icons/album_folder_bnw.png') }}" class="admin-panel-albums-albums-picture-image">                                                                   
                                <div class="admin-panel-albums-picture-and-album-title">
                                    <p>{{ $album_or_picture->name }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <a href='{{ URL::asset($pathToFile.$album_or_picture->fileName) }}'
                           data-fancybox="group" data-caption="{{ $album_or_picture->name }}" title="{{ $album_or_picture->name }}">
                            <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">
                                <div class="admin-panel-albums-pictures-picture">
                                    <div><!-- These div is required to keep normal size of image without deforming it.-->
                                        <img src="{{ URL::asset($pathToFile.$album_or_picture->fileName) }}" 
                                             alt="{{ $album_or_picture->name }}" 
                                             style="{{ ($album_or_picture->isVisible==1) ? 'opacity:1' : 'opacity:0.45' }}" 
                                             class="admin-panel-albums-pictures-picture-image">
                                    </div>
                                </div>
                                <div class="admin-panel-albums-picture-and-album-title">
                                    <p>{{ $album_or_picture->name }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field">
                    <div class="admin-panel-albums-picture-and-album-body-field-content">
                        <p>{{ $album_or_picture->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field">
                    <div class="admin-panel-albums-picture-and-album-body-field-content">
                        <p>{{ $album_or_picture->updated_at }}</p>
                    </div>
                </div>    
            </div>
        @endforeach
    </div>
</div>