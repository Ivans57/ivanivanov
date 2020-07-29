<div class='admin-panel-create-edit-entity'>
    {{ $old_keyword }}
    {!! Form::hidden(($section === 'albums') ? 'included_in_album_with_id' : 'included_in_folder_with_id', 
                    $parent_id, ['id' => 'included_in_directory_with_id']) !!}              
    <div class="admin-panel-create-edit-entity-controls">              
        <div>
            {!! Form::label('included_in_directory_with_name', 
            ($section === 'albums') ? Lang::get('keywords.ParentAlbum').':' : Lang::get('keywords.ParentFolder').':', 
            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        <div>
            {!! Form::text('included_in_directory_with_name', $parent_name, 
            ['class' => 'admin-panel-create-edit-entity-controls-input-parent', 
            'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search admin-panel-create-edit-entity-controls-button-drop-down-search">
                </span>', ['class' => 'admin-panel-create-edit-entity-controls-button-search-and-drop-down', 
                'id' => 'parent_directory_search_button', 'title' => Lang::get('keywords.FindInDataBase') ]) !!}
                {!! Form::button('<div class="admin-panel-create-edit-entity-controls-button-drop-down-caret"></div>', 
                ['class' => 'admin-panel-create-edit-entity-controls-button-search-and-drop-down 
                admin-panel-create-edit-entity-controls-button-drop-down', 
                'id' => 'parent_directory_select_from_dropdown_list_button', 'title' => Lang::get('keywords.SelectFromDropDownList') ]) !!}
            </button>
        </div>
        <div id="directory_list_container" data-previous_page="{{ Lang::get('keywords.PreviousPage') }}"
             data-next_page="{{ Lang::get('keywords.NextPage') }}" 
             data-root="{{ ($section === 'albums') ? Lang::get('keywords.Albums') : Lang::get('keywords.Articles') }}">
        </div> 
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        <div>
            {!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}</div>
    </div>
    <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
    <div class="admin-panel-create-edit-entity-controls">
        <div>{!! Form::label(($section === 'albums') ? 'album_name' : 'folder_name',
            ($section === 'albums') ? Lang::get('keywords.AlbumName').':' : Lang::get('keywords.FolderName').':',
            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        <div>{!! Form::text(($section === 'albums') ? 'album_name' : 'folder_name', null, ['class' => 'admin-panel-create-edit-entity-controls-input' ]) !!}</div>
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        {!! Form::checkbox('is_visible', 1) !!}
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-entity-controls-button', 
        'id' => 'button_submit']) !!}
        {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-create-edit-entity-controls-button', 
            'id' => 'button_cancel' ]) !!}
    </div>           
</div>