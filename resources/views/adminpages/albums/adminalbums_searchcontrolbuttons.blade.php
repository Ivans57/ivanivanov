<!-- Need to keep the first empty div to make flexes work properly.-->
<div></div>
<div class="admin-panel-albums-pictures-and-albums-control-buttons">
    <div>    
        {!! Form::button(Lang::get('keywords.Edit'), 
                        ['class' => 'admin-panel-albums-pictures-and-albums-control-button 
                          admin-panel-albums-pictures-and-albums-control-button-disabled', 
                         'id' => 'albums_button_edit', 'disabled']) !!}
    </div>
    <div>
        {!! Form::button(Lang::get('keywords.Delete'), 
                        ['class' => 'admin-panel-albums-pictures-and-albums-control-button 
                          admin-panel-albums-pictures-and-albums-control-button-disabled', 
                         'id' => 'albums_button_delete', 'disabled']) !!}
    </div>           
</div>