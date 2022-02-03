@if (($add_edit_or_delete == 'add') || ($add_edit_or_delete == 'edit'))
    <div class="admin-panel-add-edit-delete-user-to-section-controls">
        {!! Form::label('full_access', Lang::get('keywords.ProvideFullAccess').':', 
                       ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-label']) !!}
        @if ($add_edit_or_delete==='edit' && $access_status_of_first_element === 'full')
            {!! Form::checkbox('full_access', 1, true, [$users_array_size == 0 ? 'disabled' : 'enabled']) !!}
        @elseif ($add_edit_or_delete==='edit' && $access_status_of_first_element === 'limited')
            {!! Form::checkbox('full_access', 1, false, [$users_array_size == 0 ? 'disabled' : 'enabled']) !!}
        @else
            {!! Form::checkbox('full_access', 1, null, [$users_array_size == 0 ? 'disabled' : 'enabled']) !!}
        @endif
    </div>
@endif
<div class="admin-panel-add-edit-delete-user-to-section-controls">
    @if ($add_edit_or_delete == 'add')
        {!! Form::submit(Lang::get('keywords.Add'), 
                        ['class' => ($users_array_size == 0) ? 'admin-panel-add-edit-delete-user-to-section-controls-button-deactivated' : 
                         'admin-panel-add-edit-delete-user-to-section-controls-button', $users_array_size == 0 ? 'disabled' : 'enabled']) !!}
    @elseif ($add_edit_or_delete == 'edit')
        {!! Form::submit(Lang::get('keywords.Update'), 
                        ['class' => ($users_array_size == 0) ? 'admin-panel-add-edit-delete-user-to-section-controls-button-deactivated' : 
                         'admin-panel-add-edit-delete-user-to-section-controls-button', $users_array_size == 0 ? 'disabled' : 'enabled']) !!}
    @elseif ($add_edit_or_delete == 'delete')
        {!! Form::submit(Lang::get('keywords.Delete'), 
                        ['class' => ($users_array_size == 0) ? 'admin-panel-add-edit-delete-user-to-section-controls-button-deactivated' : 
                         'admin-panel-add-edit-delete-user-to-section-controls-button', $users_array_size == 0 ? 'disabled' : 'enabled']) !!}
    @endif
        {!! Form::button(Lang::get('keywords.Cancel'), 
                        ['class' => 'admin-panel-add-edit-delete-user-to-section-controls-button', 'id' => 'button_cancel']) !!}
</div>