<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
in case we don't have full page-->
<div class="admin-panel-articles-external-articles-and-folders-wrapper">
    <div class="admin-panel-articles-articles-and-folders-wrapper">
        <div class="admin-panel-articles-article-and-folder-header-row">
            <div class="admin-panel-articles-article-and-folder-header-field" id="articles_all_items_select_wrapper" 
                 title='{{ Lang::get("keywords.SelectAll") }}' 
                 data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                 {!! Form::checkbox('articles_all_items_select', 'value', false, ['id' => 'articles_all_items_select', 
                                    'class' => 'admin-panel-articles-article-and-folder-header-checkbox']); !!}
            </div>
            <div class="admin-panel-articles-article-and-folder-header-field">
                <div class="admin-panel-articles-article-and-folder-header-text-and-caret-wrapper">
                    <div class="admin-panel-articles-article-and-folder-header-text">
                        <p>@lang('keywords.Name')</p>
                    </div>
                    @if ($all_items_amount > 1)
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_name" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByNameDesc") }}'></span>
                            @else
                                <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_name" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByNameAsc") }}'></span>
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
                    @if ($all_items_amount > 1)
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_creation" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                            @else
                                <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
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
                    @if ($all_items_amount > 1)
                        <div class="admin-panel-articles-article-and-folder-header-caret">
                            @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                <span class='glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                      "admin-panel-articles-article-and-folder-header-caret-used" : 
                                      "admin-panel-articles-article-and-folder-header-caret-unused" }}'
                                      id="sort_by_update" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                            @else
                                <span class='glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
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
        @foreach ($folders as $folder)
            <div class="admin-panel-articles-article-and-folder-body-row">
                <div class="admin-panel-articles-article-and-folder-body-field">
                     {!! Form::checkbox('item_select', 1, false, 
                                       ['data-keyword' => $folder->keyword, 'data-parent_keyword' => $parent_keyword,
                                        'data-entity_type' => 'directory',  'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                        'class' => 'admin-panel-articles-article-and-folder-checkbox']); !!}
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <a href='{{ App::isLocale('en') ? "/admin/articles/".$folder->keyword."/page/1" : 
                                "/ru/admin/articles/".$folder->keyword."/page/1" }}'>
                        <div class="admin-panel-articles-article-and-folder-title-and-picture-wrapper">
                            <div>
                                <img src="{{ ($folder->is_visible==1) ? URL::asset('images/icons/regular_folder_small.png') : 
                                              URL::asset('images/icons/regular_folder_small_bnw.png') }}">                                
                            </div>
                            <div class="admin-panel-articles-article-and-folder-title">
                                <p>{{ $folder->folder_name }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-articles-article-and-folder-body-field">
                    <div class="admin-panel-articles-article-and-folder-body-field-content">
                        <p>{{ $folder->updated_at }}</p>
                    </div>
                </div>    
            </div>
        @endforeach
    </div>
</div>