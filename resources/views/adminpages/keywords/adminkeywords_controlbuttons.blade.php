<div class="admin-panel-keywords-add-keyword-wrapper">
    <div class="admin-panel-keywords-add-keyword-button">
        <a href={{ App::isLocale('en') ? "/admin/keywords/create" : "/ru/admin/keywords/create" }} 
           class="admin-panel-keywords-add-keyword-button-link" data-fancybox data-type="iframe">
           @lang('keywords.AddKeyword')
        </a>   
    </div>
</div>
<div class="admin-panel-keywords-edit-delete-control-buttons">
    <div>    
        {!! Form::button(Lang::get('keywords.Edit'), 
                        ['class' => 'admin-panel-keywords-edit-delete-control-button 
                         admin-panel-keywords-edit-delete-control-button-disabled', 
                         'id' => 'keywords_button_edit', 'disabled']) !!}
    </div>
    <div>
        {!! Form::button(Lang::get('keywords.Delete'), 
                        ['class' => 'admin-panel-keywords-edit-delete-control-button 
                         admin-panel-keywords-edit-delete-control-button-disabled', 
                         'id' => 'keywords_button_delete', 'disabled']) !!}
    </div>           
</div>