<div class="admin-panel-articles-external-articles-and-folders-wrapper">
    <div class="admin-panel-articles-articles-and-folders-wrapper">
        <div class="admin-panel-articles-article-and-folder-header-row">
            <div class="admin-panel-articles-article-and-folder-header-field" id="articles_all_items_select_wrapper" 
                 title='{{ Lang::get("keywords.SelectAll") }}' 
                 data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                 {!! Form::checkbox('articles_all_items_select', 'value', false, ['id' => 'articles_all_items_select', 
                                    'class' => 'admin-panel-articles-article-and-folder-header-checkbox']); !!}
            </div>
            <div class="admin-panel-articles-article-and-folder-header-field admin-panel-articles-article-and-folder-header-field-name">
                <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                    <div class="admin-panel-articles-article-and-folder-header-text">
                        <p>@lang('keywords.Name')</p>
                    </div>
                    @if (($articleAmount > 1 || $folderAmount > 1))
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_name" data-sorting_mode="desc" title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                            @else
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_name" data-sorting_mode="asc" title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="admin-panel-articles-article-and-folder-header-field">
                <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                    <div class="admin-panel-articles-article-and-folder-header-text">
                        <p>@lang('keywords.DateAndTimeCreated')</p>
                    </div>
                    @if (($articleAmount > 1 || $folderAmount > 1))
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_creation" data-sorting_mode="desc" 
                                      title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                            @else
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_creation" data-sorting_mode="asc" 
                                      title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="admin-panel-articles-article-and-folder-header-field">
                <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                    <div class="admin-panel-articles-article-and-folder-header-text">
                        <p>@lang('keywords.DateAndTimeUpdate')</p>
                    </div>
                    @if (($articleAmount > 1 || $folderAmount > 1))
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_update" data-sorting_mode="desc" 
                                      title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                            @else
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_update" data-sorting_mode="asc" 
                                      title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @foreach ($folders_and_articles as $folder_or_article)
            <div class="admin-panel-articles-article-and-folder-body-row">
                <div class="admin-panel-articles-article-and-folder-body-field">
                    @if ($folder_or_article->type == 'folder')
                        {!! Form::checkbox('item_select', 1, false, 
                                          ['data-keyword' => $folder_or_article->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                           'data-entity_type' => 'directory', 'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                           'class' => 'admin-panel-articles-article-and-folder-checkbox' ]); !!}
                    @else
                        {!! Form::checkbox('item_select', 1, false, 
                                          ['data-keyword' => $folder_or_article->keyWord, 'data-parent_keyword' => $parent_keyword, 
                                           'data-entity_type' => 'file',  'data-localization' => App::isLocale('en') ? 'en' : 'ru',
                                           'class' => 'admin-panel-articles-article-and-folder-checkbox' ]) !!}
                    @endif
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field admin-panel-articles-article-and-folder-body-field-name">
                    @if ($folder_or_article->type == 'folder')
                        <a href={{ App::isLocale('en') ? "/admin/articles/".$folder_or_article->keyWord."/page/1" : 
                                   "/ru/admin/articles/".$folder_or_article->keyWord."/page/1" }}>
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ ($folder_or_article->isVisible==1) ? 
                                         URL::asset('images/icons/regular_folder_small.png') : 
                                         URL::asset('images/icons/regular_folder_small_bnw.png') }}">
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->caption }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <a href={{ App::isLocale('en') ? "/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord."/".
                                   $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first : 
                                   "/ru/admin/article/".$parent_keyword."/edit/".$folder_or_article->keyWord."/".
                                   $show_invisible."/".$sorting_method_and_mode."/".$directories_or_files_first }}>
                            <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                                <div>
                                    <img src="{{ URL::asset('images/icons/article.png') }}" style="{{ ($folder_or_article->isVisible==1) ? 
                                         'opacity:1' : 'opacity:0.45' }}">
                                </div>
                                <div class="admin-panel-articles-article-and-folder-title">
                                    <p>{{ $folder_or_article->caption }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder_or_article->createdAt }}</p>    
                    </div>    
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder_or_article->updatedAt }}</p>
                    </div>
                </div>    
            </div>
        @endforeach     
    </div>
</div>