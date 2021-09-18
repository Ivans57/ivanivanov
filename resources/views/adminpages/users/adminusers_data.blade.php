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
                            <h3>@lang('keywords.Name')</h3>
                        </div>
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_keyword" data-sorting_mode="desc" 
                                          title='{{ Lang::get("keywords.SortByKeywordDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_keyword" data-sorting_mode="asc" 
                                          title='{{ Lang::get("keywords.SortByKeywordAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>               
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Email')</h3>
                        </div>                       
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Email"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Email"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_text" data-sorting_mode="desc" 
                                          title='{{ Lang::get("keywords.SortByTextDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Email"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_text" data-sorting_mode="asc"
                                          title='{{ Lang::get("keywords.SortByTextAsc") }}'></span>
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
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_creation" data-sorting_mode="desc"
                                          title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_creation" data-sorting_mode="asc"
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
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_update" data-sorting_mode="desc"
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_update" data-sorting_mode="asc"
                                          title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Role')</h3>
                        </div>
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Role"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Role"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="desc"
                                          title='{{ Lang::get("keywords.SortBySectionDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Role"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="asc"
                                          title='{{ Lang::get("keywords.SortBySectionAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="admin-panel-keywords-keywords-header-field">
                    <div class="admin-panel-keywords-keywords-header-text-and-caret-wrapper">
                        <div class="admin-panel-keywords-keywords-header-text">
                            <h3>@lang('keywords.Status')</h3>
                        </div>
                        @if ($all_users_amount > 1)
                            <div class="admin-panel-keywords-keywords-header-caret">
                                @if ($sorting_asc_or_desc["Status"][0] == "desc")
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Status"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="desc"
                                          title='{{ Lang::get("keywords.SortBySectionDesc") }}'></span>
                                @else
                                    <!-- Class sort is required only for javascript purposes.-->
                                    <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Status"][1] == "1") ? 
                                          "admin-panel-keywords-keywords-header-caret-used" : 
                                          "admin-panel-keywords-keywords-header-caret-unused" }}'
                                          id="keywords_sort_by_section" data-sorting_mode="asc"
                                          title='{{ Lang::get("keywords.SortBySectionAsc") }}'></span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @foreach ($users as $user)
                <div class="admin-panel-keywords-keywords-body-row"> 
                    <div class="admin-panel-keywords-keywords-body-field">
                        {!! Form::checkbox('item_select', 1, false, 
                                  ['data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                                   'class' => 'admin-panel-keywords-keywords-checkbox']); !!}
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            <p>{{ $user->created_at }}</p>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            <p>{{ $user->updated_at }}</p>
                        </div>
                    </div>
                    
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            <p>{{ $user->role_and_status->role }}</p>
                        </div>
                    </div>
                    <div class="admin-panel-keywords-keywords-body-field">
                        <div class="admin-panel-keywords-keywords-body-field-content">
                            @if($user->role_and_status->status = 1)
                                <p>@lang('keywords.Active')</p>
                            @else
                                <p>@lang('keywords.Inactive')</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>        
    </div>