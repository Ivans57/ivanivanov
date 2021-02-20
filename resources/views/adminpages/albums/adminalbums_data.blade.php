<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
in case we don't have full page-->
<div class="admin-panel-albums-external-pictures-and-albums-wrapper">
    <div class="admin-panel-albums-pictures-and-albums-wrapper">
        @include('adminpages.albums.adminalbums_data_header')
        @foreach ($albums as $album)
            <div class="admin-panel-albums-picture-and-album-body-row">
                <div class="admin-panel-albums-picture-and-album-body-field">
                    {!! Form::checkbox('item_select', 1, false, 
                                      ['data-keyword' => $album->keyword, 'data-parent_keyword' => $parent_keyword,
                                       'data-entity_type' => 'directory',  'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                       'class' => 'admin-panel-albums-picture-and-album-checkbox']); !!}
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field">
                    <a href='{{ App::isLocale('en') ? "/admin/albums/".$album->keyword."/page/1" : 
                            "/ru/admin/albums/".$album->keyword."/page/1" }}'>
                        <div class="admin-panel-albums-picture-and-album-title-and-picture-wrapper">                            
                            <img src="{{ ($album->is_visible==1) ? URL::asset('images/icons/album_folder.png') : 
                                          URL::asset('images/icons/album_folder_bnw.png') }}" class="admin-panel-albums-albums-picture-image">                                                                   
                            <div class="admin-panel-albums-picture-and-album-title">
                                <p>{{ $album->album_name }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field">
                    <div class="admin-panel-albums-picture-and-album-body-field-content">
                        <p>{{ $album->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-albums-picture-and-album-body-field">
                    <div class="admin-panel-albums-picture-and-album-body-field-content">
                        <p>{{ $album->updated_at }}</p>
                    </div>
                </div>    
            </div>
        @endforeach
    </div>
</div>