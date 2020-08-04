<div>
            {!! Form::text('included_in_directory_with_name', $parent_name, 
            ['class' => 'admin-panel-create-edit-entity-controls-input-parent', 
            'placeholder' => Lang::get('keywords.SearchByName').'...', 'name' => 'search']) !!}
            {!! Form::button('<span class="glyphicon glyphicon-search admin-panel-create-edit-entity-controls-button-drop-down-search">
                </span>', 
                ['class' => 'admin-panel-create-edit-entity-controls-button-search-and-drop-down
                admin-panel-create-edit-entity-controls-button-search',
                'id' => 'parent_directory_search_button', 'title' => Lang::get('keywords.FindInDataBase') ]) !!}
            {!! Form::button('<span class="glyphicon glyphicon-triangle-bottom admin-panel-create-edit-entity-controls-button-drop-down-caret">
                </span>', 
                ['class' => 'admin-panel-create-edit-entity-controls-button-search-and-drop-down 
                admin-panel-create-edit-entity-controls-button-drop-down', 
                'id' => 'parent_directory_select_from_dropdown_list_button', 'title' => Lang::get('keywords.SelectFromDropDownList') ]) !!}
        </div>