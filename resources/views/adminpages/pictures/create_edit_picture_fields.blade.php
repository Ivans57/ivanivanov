<div class='admin-panel-create-edit-entity'>
    {{ $old_keyword }}
    {!! Form::hidden('included_in_album_with_id', 
                    $parent_id, ['id' => 'included_in_directory_with_id']) !!}              
    <div class="admin-panel-create-edit-entity-controls">              
        <div>
            {!! Form::label('included_in_directory_with_name', 
            Lang::get('keywords.ParentAlbum').':', 
            ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        @component('adminpages/create_edit_parent_search', ['parent_name' => $parent_name])
        @endcomponent
        <div id="directory_list_container" data-previous_page="{{ Lang::get('keywords.PreviousPage') }}"
             data-next_page="{{ Lang::get('keywords.NextPage') }}" 
             data-root="{{ Lang::get('keywords.Albums') }}">
        </div> 
    </div>   
    <div class="admin-panel-create-edit-entity-controls">
        @if ($create_or_edit==='create')
        <div class='admin-panel-create-edit-entity-controls-preview-picture' id="image_select_preview">
            <div class='admin-panel-create-edit-entity-controls-preview-picture-template'><span>{{ Lang::get('keywords.Sketch') }}</span></div>
        </div>
            <div>{!! Form::button(Lang::get('keywords.Browse')."...", ['class' => 'admin-panel-create-edit-entity-controls-browse-picture-button', 
                    'id' => 'pseudo_image_select' ]) !!}
                <span id = 'pseudo_image_select_file_name' 
                        class = 'admin-panel-create-edit-entity-controls-browse-picture-text'>
                    {{ Lang::get('keywords.NoImageChosen') }}
                </span>
            </div>
            <div>{!! Form::file('image_select', ['id' => 'image_select', 'style' => 'display:none'] ) !!}</div>
        @else
            <div class='admin-panel-create-edit-entity-controls-preview-picture' id="image_select_preview">
                <div>
                    <a href="{{ URL::asset($path_to_file.$file_name) }}" data-fancybox="group" id="image_preview_link">
                        <img id='image_preview' src="{{ URL::asset($path_to_file.$file_name) }}">
                    </a>
                </div>
            </div>
        @endif
    </div>  
    <div class="admin-panel-create-edit-entity-controls">
        <div>
            {!! Form::label('keyword', Lang::get('keywords.PictureKeyword').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        <div>{!! Form::text('keyword', null, ['class' => 'admin-panel-create-edit-entity-controls-input']) !!}</div>
    </div>                
    <div class="admin-panel-create-edit-entity-regulations"><span>@lang('keywords.AlbumKeywordRegulations')</span></div>
    <div class="admin-panel-create-edit-entity-controls">
        <div>{!! Form::label('picture_caption', Lang::get('keywords.PictureCaption').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        </div>
        <div>{!! Form::text('picture_caption', null, ['class' => 'admin-panel-create-edit-entity-controls-input' ]) !!}</div>
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::label('is_visible', Lang::get('keywords.IsVisible').':', ['class' => 'admin-panel-create-edit-entity-controls-label']) !!}
        {!! Form::checkbox('is_visible', 1) !!}
    </div>
    <div class="admin-panel-create-edit-entity-controls">
        {!! Form::submit(Lang::get('keywords.Save'), ['class' => 'admin-panel-create-edit-entity-controls-button', 
        'name' => $create_or_edit==='create' ? 'upload' : 'save', 'id' => 'button_submit' ]) !!}
        {!! Form::button(Lang::get('keywords.Cancel'), ['class' => 'admin-panel-create-edit-entity-controls-button', 
            'id' => 'button_cancel' ]) !!}
    </div>           
</div>