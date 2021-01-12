<!-- We need external wrapper to keep pagination buttons in the bottom of article sectional
    in case we don't have full page-->
    <div class="admin-panel-keywords-external-keywords-wrapper">
        <div class="admin-panel-keywords-keywords-wrapper">
            <div class="admin-panel-keywords-keywords-header-row">
                <div class="admin-panel-keywords-keywords-header-field" id="keywords_all_items_select_wrapper" 
                                     title='{{ Lang::get("keywords.SelectAll") }}' 
                                     data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                                    {!! Form::checkbox('keywords_all_items_select', 'value', false, ['id' => 'keywords_all_items_select', 
                                    'class' => 'admin-panel-keywords-keywords-header-checkbox']); !!}
                </div>
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Keyword')</h3>
                        </div>
                        @if ($all_keywords_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Keyword"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Keyword"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_keyword" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }} 
                                          title='{{ Lang::get("keywords.SortByKeywordDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Keyword"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_keyword" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }} 
                                          title='{{ Lang::get("keywords.SortByKeywordAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>               
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Text')</h3>
                        </div>                       
                        @if ($all_keywords_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Text"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Text"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_text" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }} 
                                          title='{{ Lang::get("keywords.SortByTextDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Text"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_text" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortByTextAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>                
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Section')</h3>
                        </div>
                        @if ($all_keywords_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Section"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Section"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortBySectionDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Section"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortBySectionAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>              
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.DateAndTimeCreated')</h3>
                        </div>
                        @if ($all_keywords_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_creation" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_creation" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.DateAndTimeUpdate')</h3>
                        </div>
                        @if ($all_keywords_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_update" data-sorting_mode="desc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_update" data-sorting_mode="asc" data-search_is_on={{ $search_is_on }}
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @foreach ($keywords as $keyword)
                <div class="admin-panel-keywords-keywords-body-row"> 
                    <div class="admin-panel-keywords-keywords-body-field">
                        {!! Form::checkbox('item_select', 1, false, 
                                  ['data-keyword' => $keyword->keyword, 'data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                   'class' => 'admin-panel-keywords-keywords-checkbox']); !!}
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <p>{{ $keyword->keyword }}</p>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <p>{{ $keyword->text }}</p>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <p>{{ $keyword->section }}</p>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            <p>{{ $keyword->created_at }}</p>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            <p>{{ $keyword->updated_at }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>        
    </div>