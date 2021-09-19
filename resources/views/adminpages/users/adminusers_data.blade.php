<div class="admin-panel-users-external-users-wrapper">
    <div class="admin-panel-users-users-wrapper">
        <div class="admin-panel-users-users-header-row">
            <div class="admin-panel-users-users-header-field" id="users_all_items_select_wrapper" 
                                 title='{{ Lang::get("keywords.SelectAll") }}' 
                                 data-select='{{ Lang::get("keywords.SelectAll") }}' data-unselect='{{ Lang::get("keywords.UnselectAll") }}'>
                                 {!! Form::checkbox('users_all_items_select', 'value', false, ['id' => 'users_all_items_select', 
                                 'class' => 'admin-panel-users-users-header-checkbox']); !!}
            </div>
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                        <h3>@lang('keywords.UserName')</h3>
                    </div>
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Name"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_name" data-sorting_mode="desc" 
                                      title='{{ Lang::get("keywords.SortByUserNameDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Name"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_name" data-sorting_mode="asc" 
                                      title='{{ Lang::get("keywords.SortByUserNameAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>               
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                            <h3>@lang('keywords.Email')</h3>
                    </div>                       
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Email"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Email"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_email" data-sorting_mode="desc" 
                                      title='{{ Lang::get("keywords.SortByEmailDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Email"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_email" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByEmailAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>                              
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                        <h3>@lang('keywords.DateAndTimeCreated')</h3>
                    </div>
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Creation"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_creation" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByCreationDateAndTimeDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Creation"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_creation" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByCreationDateAndTimeAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                        <h3>@lang('keywords.DateAndTimeUpdate')</h3>
                    </div>
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Update"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_update" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByUpdateDateAndTimeDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Update"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_update" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByUpdateDateAndTimeAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>              
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                        <h3>@lang('keywords.Role')</h3>
                    </div>
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Role"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Role"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_role" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByRoleDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Role"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_role" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByRoleAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="admin-panel-users-users-header-field">
                <div class="admin-panel-users-users-header-text-and-caret-wrapper">
                    <div class="admin-panel-users-users-header-text">
                        <h3>@lang('keywords.Status')</h3>
                    </div>
                    @if ($all_users_amount > 1)
                        <div class="admin-panel-users-users-header-caret">
                            @if ($sorting_asc_or_desc["Status"][0] == "desc")
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-bottom {{ ($sorting_asc_or_desc["Status"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_status" data-sorting_mode="desc"
                                      title='{{ Lang::get("keywords.SortByStatusDesc") }}'></span>
                            @else
                                <!-- Class sort is required only for javascript purposes.-->
                                <span class='sort glyphicon glyphicon-triangle-top {{ ($sorting_asc_or_desc["Status"][1] == "1") ? 
                                      "admin-panel-users-users-header-caret-used" : 
                                      "admin-panel-users-users-header-caret-unused" }}'
                                      id="users_sort_by_status" data-sorting_mode="asc"
                                      title='{{ Lang::get("keywords.SortByStatusAsc") }}'></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>           
        @foreach ($users as $user)
            <div class="admin-panel-users-users-body-row"> 
                <div class="admin-panel-users-users-body-field">
                    {!! Form::checkbox('item_select', 1, false, 
                              ['data-localization' => App::isLocale('en') ? 'en' : 'ru', 
                               'class' => 'admin-panel-users-users-checkbox']); !!}
                </div>
                <div class="admin-panel-users-users-body-field">
                    <p>{{ $user->name }}</p>
                </div>
                <div class="admin-panel-users-users-body-field">
                    <p>{{ $user->email }}</p>
                </div>
                <div class="admin-panel-users-users-body-field">
                    <div class="admin-panel-users-users-body-field-content">
                        <p>{{ $user->created_at }}</p>
                    </div>
                </div>
                <div class="admin-panel-users-users-body-field">
                    <div class="admin-panel-users-users-body-field-content">
                        <p>{{ $user->updated_at }}</p>
                    </div>
                </div>              
                <div class="admin-panel-users-users-body-field">
                    <div class="admin-panel-users-users-body-field-content">
                        <p>{{ $user->role_and_status->role }}</p>
                    </div>
                </div>
                <div class="admin-panel-users-users-body-field">
                    <div class="admin-panel-users-users-body-field-content">
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