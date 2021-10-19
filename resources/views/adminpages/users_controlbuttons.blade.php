<div class="admin-panel-user-control-buttons">
    <div class="admin-panel-user-control-button-wrapper">
        {!! Form::button(Lang::get('keywords.AddUser'), ['class' => 'admin-panel-user-control-button', 'id' => 'add_user_button']) !!}
    </div>
    <div class="admin-panel-user-control-button-wrapper">    
        {!! Form::button(Lang::get('keywords.EditUser'), ['class' => 'admin-panel-user-control-button', 'id' => 'edit_user_button']) !!}
    </div>
    <div class="admin-panel-user-control-button-wrapper">
        {!! Form::button(Lang::get('keywords.DeleteUser'), ['class' => 'admin-panel-user-control-button', 'id' => 'delete_user_button']) !!}
    </div>           
</div>