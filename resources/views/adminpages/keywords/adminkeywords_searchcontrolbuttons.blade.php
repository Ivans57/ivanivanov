<div></div>
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