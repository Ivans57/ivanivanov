<div class='admin-panel-create-edit-directory'>
    {{ $old_keyword }}
    <!-- In case with pictures always have to be null. As we are using the same javascript with directories and pictures,
    we have to keep this field to avoid an error (need to try to get rid of it.)-->
    {!! Form::hidden('included_in_album_with_id', 
                    $parent_id, ['id' => 'included_in_directory_with_id']) !!}              
    <div class="admin-panel-create-edit-directory-controls">              
        <div>
            {!! Form::label('included_in_directory_with_name', 
            Lang::get('keywords.ParentAlbum').':', 
            ['class' => 'admin-panel-create-edit-directory-controls-label']) !!}
        </div>
        <div>
            {!! Form::text('included_in_directory_with_name', $parent_name, 
            ['class' => 'admin-panel-create-edit-directory-controls-input-parent', 
            'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search admin-panel-create-edit-directory-controls-button-drop-down-search">
                </span>', ['class' => 'admin-panel-create-edit-directory-controls-button-search-and-drop-down', 
                'id' => 'parent_directory_search_button', 'title' => Lang::get('keywords.FindInDataBase') ]) !!}
                {!! Form::button('<div class="admin-panel-create-edit-directory-controls-button-drop-down-caret"></div>', 
                ['class' => 'admin-panel-create-edit-directory-controls-button-search-and-drop-down 
                admin-panel-create-edit-directory-controls-button-drop-down', 
                'id' => 'parent_directory_select_from_dropdown_list_button', 'title' => Lang::get('keywords.SelectFromDropDownList') ]) !!}
            </button>
        </div>
        <div id="directory_list_container" data-previous_page="{{ Lang::get('keywords.PreviousPage') }}"
             data-next_page="{{ Lang::get('keywords.NextPage') }}" 
             data-root="{{ Lang::get('keywords.Albums') }}">
        </div> 
    </div>
    <div class="admin-panel-create-edit-directory-controls">
        <div>{!! Form::button(Lang::get('keywords.Browse')."...", ['style' => "display:block; width:80px; height:25px; font-size:14px; float:left;", 
            'id' => 'pseudo_image_select' ]) !!}
            <!-- Need to shorten long file names with java script-->
            <span id = 'pseudo_image_select_file_name' 
                  style='display:inline-block; margin-left:10px; margin-top:3px; font-size:14px; width: 170px; text-overflow: ellipsis;'>
                {{ Lang::get('keywords.NoImageChosen') }}
            </span>
        </div>
        <div>{!! Form::file('image_select', ['id' => 'image_select', 'style' => 'display:none'] ) !!}</div>
    </div>
    <div class="admin-panel-create-edit-directory-controls">
        <div>
            {!! Form::label('keyword', Lang::get('keywords.Keyword').':', ['class' => 'admin-panel-create-edit-directory-controls-label']) !!}
        </div>
        <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-create-edit-directory-controls-input']) !!}</div>
    </div>                
    <div class="admin-panel-create-edit-directory-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
    <div class="admin-panel-create-edit-directory-controls">
        <div>{!! Form::label('album_name', Lang::get('keywords.AlbumName').':', ['class' => 'admin-panel-create-edit-directory-controls-label']) !!}
        </div>
        <div>{!! Form::text('album_name', null, ['class' => 'admin-panel-create-edit-directory-controls-input' ]) !!}</div>
    </div>
    <div class="admin-panel-create-edit-directory-controls">
        {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-create-edit-directory-controls-label']) !!}
        {!! Form::checkbox('is_visible', 1) !!}
    </div>
    <div class="admin-panel-create-edit-directory-controls">
        {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-directory-controls-button', 
        'id' => 'button_submit']) !!}
        {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-create-edit-directory-controls-button', 
            'id' => 'button_cancel' ]) !!}
    </div>           
</div>