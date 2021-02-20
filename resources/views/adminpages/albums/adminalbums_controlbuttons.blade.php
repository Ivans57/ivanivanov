<div class="admin-panel-albums-add-picture-album-wrapper">
    <div class="admin-panel-albums-add-album-button">
        <a href='{{ App::isLocale('en') ? "/admin/albums/create/".$parent_keyword : "/ru/admin/albums/create/".$parent_keyword }}' 
           class="admin-panel-albums-add-album-button-link" data-fancybox data-type="iframe">@lang('keywords.AddAlbum')</a>
    </div>
</div>
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