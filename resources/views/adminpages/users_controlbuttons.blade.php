<div class="admin-panel-user-control-buttons">
        {!! Form::hidden('useful_data', '0', ['id' => 'useful_data', 'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                              'data-section' => $section]); !!}
        <div class="admin-panel-user-control-button-wrapper">
            {!! Form::button(Lang::get('keywords.AddUser'), ['class' => 'admin-panel-user-control-button', 'id' => 'add_user_button']) !!}
        </div>
        <div class="admin-panel-user-control-button-wrapper">    
            {!! Form::button(Lang::get('keywords.EditUserPermissions'), ['class' => 'admin-panel-user-control-button', 'id' => 'edit_user_button']) !!}
        </div>
        <div class="admin-panel-user-control-button-wrapper">
            {!! Form::button(Lang::get('keywords.DeleteUser'), ['class' => 'admin-panel-user-control-button', 'id' => 'delete_user_button']) !!}
        </div>
</div>