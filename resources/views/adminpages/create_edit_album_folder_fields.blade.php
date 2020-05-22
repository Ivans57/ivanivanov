<div class='admin-panel-albums-create-edit-album'>
    {{ $hidden_field }}
    <!-- <div class="admin-panel-albums-create-edit-album-controls">              
        {!! Form::label('included_in_album_with_id', Lang::get('keywords.ParentAlbum').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
        {!! Form::select('included_in_album_with_id', $albums, $parent_id, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}
    </div> -->
    <div class="admin-panel-albums-create-edit-album-controls">              
        <div>{!! Form::label('included_in_album_with_id', Lang::get('keywords.ParentAlbum').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
        <div>{!! Form::text('included_in_album_with_id', null, 
            ['class' => 'admin-panel-albums-create-edit-album-controls-input-parent', 'placeholder' => 'Search...', 'name' => 'search']) !!}
            {!! Form::button('<i class="fas fa-search fa-sm"></i>', ['class' => 'admin-panel-albums-create-edit-album-controls-button-search', 
                'id' => 'parent_albums_search_button', 'title' => "Search in Data Base" ]) !!}
            </button>
        </div>
        <div id="album_list_container"></div>
    </div>
    <div class="admin-panel-albums-create-edit-album-controls">
        <div>{!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
        <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input']) !!}</div>
    </div>
    <div class="admin-panel-albums-create-edit-album-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
    <div class="admin-panel-albums-create-edit-album-controls">
        <div>{!! Form::label('album_name', Lang::get('keywords.AlbumName').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}</div>
        <div>{!! Form::text('album_name', null, ['class' => 'admin-panel-albums-create-edit-album-controls-input' ]) !!}</div>
    </div>
    <div class="admin-panel-albums-create-edit-album-controls">
        {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-albums-create-edit-album-controls-label']) !!}
        {!! Form::checkbox('is_visible', 1) !!}
    </div>
        <div class="admin-panel-albums-create-edit-album-controls">
            {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-albums-create-edit-album-controls-button' ]) !!}
            {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-albums-create-edit-album-controls-button', 
                'id' => 'admin_panel_albums_create_edit_delete_album_controls_button_cancel' ]) !!}
        </div>           
</div>