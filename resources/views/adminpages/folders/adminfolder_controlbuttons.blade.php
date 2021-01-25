<div class="admin-panel-articles-add-article-folder-wrapper">
    <div class="admin-panel-articles-add-article-button">
        <a href={{ App::isLocale('en') ? "/admin/article/create/".$parent_keyword."/".
                                         $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first : 
                                         "/ru/admin/article/create/".$parent_keyword."/".
                                         $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first }} 
                                         class="admin-panel-articles-add-article-button-link">
                                         @lang('keywords.AddArticle')
        </a>
    </div>
    @if ($nesting_level < 7)
        <div class="admin-panel-articles-add-folder-button">
            <a href={{ App::isLocale('en') ? "/admin/articles/create/".$parent_keyword : "/ru/admin/articles/create/".$parent_keyword }} 
                                             class="admin-panel-articles-add-folder-button-link" data-fancybox data-type="iframe">
                                             @lang('keywords.AddFolder')
            </a>
        </div>
    @endif
</div>
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