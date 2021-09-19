<div class="admin-panel-users-add-user-wrapper">
    <div class="admin-panel-users-add-user-button">
        <a href='{{ App::isLocale('en') ? "/admin/users/create" : "/ru/admin/users/create" }}' 
           class="admin-panel-users-add-user-button-link" data-fancybox data-type="iframe">
           @lang('keywords.AddUser')
        </a>   
    </div>
</div>
<div class="admin-panel-users-edit-delete-control-buttons">
    <div>    
        {!! Form::button(Lang::get('keywords.Edit'), 
                        ['class' => 'admin-panel-users-edit-delete-control-button 
                         admin-panel-users-edit-delete-control-button-disabled', 
                         'id' => 'users_button_edit', 'disabled']) !!}
    </div>
    <div>
        {!! Form::button(Lang::get('keywords.Delete'), 
                        ['class' => 'admin-panel-users-edit-delete-control-button 
                         admin-panel-users-edit-delete-control-button-disabled', 
                         'id' => 'users_button_delete', 'disabled']) !!}
    </div>           
</div>