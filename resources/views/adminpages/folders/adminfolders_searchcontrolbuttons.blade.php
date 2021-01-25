<!-- Need to keep the first empty div to make flexes work properly.-->
<div></div>
<div class="admin-panel-articles-article-and-folder-control-buttons">
    <div>    
        {!! Form::button(Lang::get('keywords.Edit'), 
                        ['class' => 'admin-panel-articles-article-and-folder-control-button 
                          admin-panel-articles-article-and-folder-control-button-disabled', 
                         'id' => 'articles_button_edit', 'disabled']) !!}
    </div>
    <div>
        {!! Form::button(Lang::get('keywords.Delete'), 
                        ['class' => 'admin-panel-articles-article-and-folder-control-button 
                          admin-panel-articles-article-and-folder-control-button-disabled', 
                         'id' => 'articles_button_delete', 'disabled']) !!}
    </div>
</div>